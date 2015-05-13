<?php

namespace App\Controller;

use App\Lib\Tool\Generator;
use App\Lib\Tool\Request;
use App\Model\Position;
use RuntimeException;

class FrontController
{
    protected $_model;
    protected $_request;

    protected $_positionId = 0;

    public function __construct()
    {
        $this->model = new Position();
        $this->request = new Request();
    }

    /**
     *
     */
    public function indexAction()
    {
        // существует номер позиции  кораблей в пост запросе
        if (!$this->request->isPost() && $this->request->hasRefer()) {
            $this->_positionId = $this->request->refer('id');
        }

        $position = ($this->_positionId < 1)
            ? $this->_create()
            : $this->_load();


        $this->_render($position);
    }

    /**
     * Создание новой позиции.
     *
     * @return array
     */
    protected function _create()
    {
        $position = (new Generator())->run();

        if (empty($position)) {
            throw new RuntimeException('Not create new position');
        }

        // созраняем позицию в базу данных для загрузки
        $data = implode(',', $position);
        $this->_positionId = $this->model->add($data);

        return $position;
    }

    /**
     * @return array
     */
    protected function _load()
    {
        // загружаем из базы данных
        $data = $this->model->get($this->_positionId);

        if (empty($data['data'])) {
            throw new RuntimeException('Position not find');
        }

        $position = explode(',', $data['data']);

        return $position;
    }

    /**
     * @param array $data
     */
    protected function _render($data)
    {
        $response = [
            'success' => [
                'id' => (int)$this->_positionId,
                'position' => $data
            ]
        ];

        echo json_encode($response);
    }
}
