<?php
namespace App\Model;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Position extends Base
{
    /**
     * @param string $data
     * @return mixed
     */
    public function add($data)
    {
        $params = ['data' => $data];
        $sql = "INSERT INTO `sea_fight_position` (`data`) VALUES (:data)";

        return $this->_db->insert($sql, $params);
    }

    /**
     * @param int $id
     * @return array|null
     */
    public function get($id)
    {
        $params = ['id' => $id];
        $sql = "SELECT * FROM `sea_fight_position` WHERE id=:id";

        $result = $this->_db->fetchRow($sql, $params);

        if (!empty($result)) {
            return $result;
        }
    }
}
