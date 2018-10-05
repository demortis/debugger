<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 11:29
 */

namespace debugger\core\exceptions;


class ClassNotExistsException extends \ReflectionException
{
    public function getName()
    {
        return 'Class not exists ('.$this->getMessage().')';
    }
}