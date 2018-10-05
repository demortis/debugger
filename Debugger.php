<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 05.09.18
 * Time: 15:12
 */

namespace debugger;

include_once 'autoload.php';

use debugger\core\DebugObject;
use debugger\core\exceptions\UndefinedModuleException;
use debugger\core\interfaces\Module;

/**
 * Класс обертка для объекта DebugModule
 * Сделан для удобства работы с модулями через статический вызов (Debugger::{moduleName}())
 * Class Debugger
 * @package debugger
 */
class Debugger
{
    /**
     * Метод возвращает экземпляр модуля при вызове его Debugger::{$moduleName}()
     * Если модуль отсутствует или не наследуется от нашего абстрактного класса
     * выбрасывается исключение.
     * Если подмодули вызывают исключение они записываются в массив $exceptions
     * @param string $name имя подключенного модуля из config файла.
     * @param array $properties параметры передаваемые в конструктор модуля
     * @return DebugModule
     * @throws UndefinedModuleException модуль незарегистрирован в config
     * */
    public static function __callStatic(string $name, array $properties = []): ?Module
    {
        $debugger = DebugModule::get();

        try {
            $module = $debugger->submodules->getModuleByName($name);
            DebugObject::configure($module, $properties);
            return $module;
        } catch (\Throwable $e) {
            $debugger->addException($name, $e);
        }

        return null;
    }

    /**
     * Метод обертка для вызова метода класса DebugModule отображающего панель.
     * @return string
     */
    public static function getPanel(): string
    {
        return DebugModule::getPanel();
    }
}