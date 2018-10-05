<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.09.18
 * Time: 13:23
 */

namespace debugger\core\exceptions;

class UndefinedModuleException extends \BadMethodCallException
{
    public function getName() : string
    {
        return 'Undefined module - '.$this->getMessage();
    }
}