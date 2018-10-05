<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 13:57
 */

namespace debugger\core\exceptions;


class ModuleNotExistsException extends \ReflectionException
{
    public function getName()
    {
        return 'Module not exists ('.$this->getMessage().')';
    }
}