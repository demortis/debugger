<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 11.09.18
 * Time: 13:46
 */


namespace debugger;

/**
 * Простой класс автозагрузки
 * Class Autoloader
 * @package debugger
 */
class Autoloader {

    /**
     * Метод отдает корневой путь.
     * ! Не используем $_SERVER['DOCUMENT_ROOT']
     *   потому что при дебаг через консоль такое не знает.
     * @return string
     */
    public static function getRoot() : string
    {
        return dirname(__DIR__, 1);
    }

    /**
     *  Метода инициализирует автозагрзку
     *  В стеке автозагрузчиков вперед не встаем.
     */
    public static function init()
    {
        spl_autoload_register([self::class, 'load'], false);
    }

    /**
     * Метод загружает файл класса
     * @param $class
     */
    public static function load(string $class)
    {
        $path = str_replace('\\', DIRECTORY_SEPARATOR, $class);
        include_once self::getRoot().DIRECTORY_SEPARATOR.$path.'.php';
    }
}

Autoloader::init();

