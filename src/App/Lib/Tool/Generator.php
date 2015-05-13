<?php

namespace App\Lib\Tool;

use App\Lib\Type\LocationInterface;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Generator implements LocationInterface
{
    protected $_response = [];
    protected $_board = [];

    /**
     * Размер игрового поля.
     */
    const COUNT_ITEM = 10;

    public function __construct()
    {
        for ($i = 1; $i <= self::COUNT_ITEM; $i++) {

            $this->_board[$i] = [];

            for ($j = 1; $j <= self::COUNT_ITEM; $j++) {
                $this->_board[$i][$j] = 1;
            }
        }
    }

    public function run()
    {
        $list = $this->_getShipsList();

        foreach ($list as $key => $value) {
            $location = (rand(0, 1) > 0) ? self::VERTICAL : self::HORIZONTAL;

            do {
                if ($location === self::VERTICAL) {
                    $x = rand(1, self::COUNT_ITEM - $value);
                    $y = rand(1, self::COUNT_ITEM);
                } else {
                    $x = rand(1, self::COUNT_ITEM);
                    $y = rand(1, self::COUNT_ITEM - $value);
                }

                $status = $this->_checkPosition($location, $value, $x, $y);
            } while ($status != true);

            $this->_takePosition($location, $value, $x, $y);

            $this->_addResponse($location, $value, $x, $y);
        }

        return $this->_response;
    }

    /**
     * Список кораблей.
     */
    protected function _getShipsList()
    {
        return [4, 3, 3, 2, 2, 2, 1, 1, 1, 1];
    }

    /**
     * Проверка проверка координат корабля.
     */
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
            for ($i = $x - 1; $i <= ($x + $n + 1); $i++) {
                for ($j = $y - 1; $j <= $y + 1; $j++) {
                    if (isset($this->_board[$i][$j])) {
                        $this->_board[$i][$j] = 0;
                    }
                }
            }
        } else {
            for ($i = $y - 1; $i <= ($y + $n + 2); $i++) {
                for ($j = $x - 1; $j <= $x + 1; $j++) {
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
}
