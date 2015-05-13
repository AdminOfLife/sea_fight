<?php

namespace App\Lib\Tool;

/**
 *
 * @package App
 * @author Dmitriy Rudenskiy <dmitriy.rudenskiy@gmail.com>
 * @version 1.0.0
 */
class Request
{
    public function isPost()
    {
        return !empty($_POST);
    }

    public function hasRefer()
    {
        if (empty($_SERVER["HTTP_REFERER"])) {
            return false;
        }

        $url = parse_url($_SERVER["HTTP_REFERER"], PHP_URL_QUERY);

        return empty($url['query']);


    }

    /**
     * Получаем переменный из $_GET параметр
     * страницы с которой пришёл запрос.
     *
     * @param string $key
     * @return string|null
     */
    public function refer($key)
    {
        parse_str(parse_url($_SERVER["HTTP_REFERER"], PHP_URL_QUERY), $data);

        return (isset($data[$key])) ? $data[$key] : null;
    }
}
