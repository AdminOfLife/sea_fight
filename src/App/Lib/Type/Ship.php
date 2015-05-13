<?php

namespace App\Lib\Type;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Ship
{
    protected $_location;
    protected $_size = 0;
    protected $_positionX = 0;
    protected $_positionY = 0;

    public function __constructor($location, $size, $x, $y)
    {
    }
}
