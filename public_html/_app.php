<?php

/*
 * CREATE TABLE `sea_fight_position` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `data` VARCHAR(50) NULL,
  PRIMARY KEY (`id`));

 */

$id = 0;

if (!empty($_SERVER["REQUEST_URI"]) && strlen($_SERVER["REQUEST_URI"]) > 1) {
	$id = (int)substr($_SERVER["REQUEST_URI"], 1);
}

?>


<?php

class Db_Mysql
{
	protected $_db = null;
	
	const HOST = 'localhost';
	const LOGIN = 'u001';
	const PASSWORD = 'p001';
	const NAME = 'u001';

	public function __construct()
	{
		$this->_db = mysql_connect(self::HOST, self::LOGIN, self::PASSWORD);
		mysql_select_db(self::NAME, $this->_db);
		mysql_set_charset('utf8', $this->_db);
	}


	public function fetchRow($sql = '')
	{
		if ($this->_db == null || empty($sql)) {
			return 0;
		}

		$result = mysql_query($sql, $this->_db);

		if (mysql_num_rows($result) > 0) {
			return mysql_fetch_assoc($result);
		}

		return 0;
	}

	public function exec($sql = '')
	{
		if ($this->_db == null || empty($sql)) {

		}

		try {
			mysql_query($sql, $this->_db);
		} catch (Exception $e) {
			return false;
		}

		return true;
	}
	
	public function getPosition($id)
	{
		$sql = "SELECT `position` FROM `battleship` WHERE `id`=" . (int)$id;
		
		$result = $this->fetchRow($sql); 
		
		return $result;
	}
	
	 public function addPosition($text)
	 {
	 	$sql = "INSERT INTO `u001`.`battleship` (`position`)
	 			VALUES ('".$text."');";
	 	
	 	$result = $this->exec($sql);
	 	
	 	return $result;
	 }
}

class Generator
{
	protected $_response = array();
	protected $_board = array();
	const COUNT_ITEM = 10;
	
	public function run()
	{
		$this->_clear();
		
		foreach ($this->_getShipsList() as $key => $value) {
			
			$isVertical = rand(0, 1);
			
			do {
				
				// определяем вертикальный или горизонтальный
				if ($isVertical == 1) {
					$isVertical = 0;
				} else {
					$isVertical = 1;
				}
				
				if ($isVertical == 1) {
					$x = rand(1, self::COUNT_ITEM - $value);
					$y = rand(1, self::COUNT_ITEM);
				} else {
					$x = rand(1, self::COUNT_ITEM);
					$y = rand(1, self::COUNT_ITEM - $value);
				}
				
				$status = $this->_checkPosition($isVertical, $value, $x, $y);
				
			} while ($status != true);
			
			$this->_takePosition($isVertical, $value, $x, $y);
			
			$this->_addResponse($isVertical, $value, $x, $y);
		}
	}
	
	protected function _clear()
	{
		for ($i = 1; $i <= self::COUNT_ITEM; $i++) {
			for ($j = 1; $j <= self::COUNT_ITEM; $j++) {
				$this->_board[$i][$j] = 1;
			}
		}
	}
	
	protected function _getShipsList()
	{
		return array(
			0 => 4,
			1 => 3,
			2 => 3,
			3 => 2,
			4 => 2,
			5 => 2,
			6 => 1,
			7 => 1,
			8 => 1,
			9 => 1
		);
	}
	
	protected function _checkPosition($isVertical, $n, $x, $y)
	{
		if ($isVertical == 1) {
			for ($i = $x; $i <= $x + $n; $i++) {
				if ($this->_board[$i][$y] == 0) {
					return false;
				}
			}
		} else {
			for ($i = $y; $i <= $y + $n; $i++) {
				if ($this->_board[$x][$i] == 0) {
					return false;
				}
			}
		}
		
		return true;
		
	}
	
	protected function _takePosition($isVertical, $n, $x, $y)
	{
		if ($isVertical == 1) {
			for ($i = $x -1; $i <= ($x + $n + 1); $i++) {
				
				for ($j = $y - 1; $j <= $y +1; $j++) {
					if (isset($this->_board[$i][$j])) {
						$this->_board[$i][$j] = 0;
					}
				}
				
			}
		} else {
			for ($i = $y - 1; $i <= ($y + $n + 2); $i++) {
				
				for ($j = $x - 1; $j <= $x +1; $j++) {
					if (isset($this->_board[$j][$i])) {
						$this->_board[$j][$i] = 0;
					}
				}
				
			}
		}
	}
	
	protected function _addResponse($isVertical, $n, $x, $y)
	{
		if ($isVertical == 1) {
			for ($i = $x; $i < $x + $n; $i++) {
				$this->_response[] = $i + $y * self::COUNT_ITEM;
			}
		} else {
			for ($i = $y; $i < $y + $n; $i++) {
				$this->_response[] = $x + $i * self::COUNT_ITEM;
			}
		}
	}
	
	public function __toString()
	{
		return json_encode($this->_response);
	}
}

$db = new Db_Mysql();

if (isset($_POST["id"]) && $_POST["id"] > 0) {
	// загружаем из базы данных
	$position = $db->getPosition($_POST["id"]);
	
	if (!empty($position["position"])) {
		echo $position["position"];
		exit();
	}
}



$generator = new Generator();
$generator->run();

$position = $generator->__toString();

$db->addPosition($position);

echo $position;
exit();
