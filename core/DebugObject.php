<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 11:23
 */

namespace debugger\core;

use debugger\core\exceptions\ClassNotExistsException;
use debugger\core\interfaces\Executive;
use debugger\DebugModule;

class DebugObject
{
    public static function create(string $class, array $attributes = []): DebugObject
    {
        if (!class_exists($class)) {
            throw new ClassNotExistsException($class);
        }

        $object = is_subclass_of($class, DebugModule::class) ? $class::get() : new $class;

        self::configure($object, $attributes);

        if ($object instanceof Executive) {
            $object->execute();
        }

        return $object;
    }

    /**
     * @param DebugObject $object
     * @param array $attributes
     * TODO при реконструкции модуля учесть параметры не объявленные в $properties. Возвращать их к дефолтовым значениям, либо обnullять.
     */
    public static function configure(DebugObject $object, array $attributes): void
    {
        if (!empty($attributes)) {
            foreach ($attributes as $name => $attribute) {
                if (property_exists($object, $name)) {
                    if ($name === 'modules' || $name === 'submodules') {
                        $object->$name = new ModuleCollection($attribute, $object);
                    } else {
                        $object->$name = $attribute;
                    }
                }
            }
        }
    }
}