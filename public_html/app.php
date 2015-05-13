<?php 
try {
    // автозагрузчик
    require(__DIR__ . '/../vendor/autoload.php');

    // настраиваем соединение  базой данных
    App\Lib\Db\Adapter::init(include '../src/App/config/config.php');

    // запуск приложения
    (new App\Controller\FrontController())->indexAction();
} catch (Exception $e) {
    // прячем ошибки
    //echo 'Ошибка';

    // отображаем ошибки
    echo $e->getMessage();
}

