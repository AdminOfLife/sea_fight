<?php
namespace App\Model;

use App\Lib\Db\Adapter;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Base
{

    protected $_db;

    /**
    * Constructor
    *
    * Instantiates the adapter class.
    */
    public function __construct()
    {
        $this->_db = new Adapter();
    }
}
