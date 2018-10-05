<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.09.18
 * Time: 14:16
 */

namespace debugger\modules\sql\exceptions;

class UndefinedQueryTypeException extends \InvalidArgumentException
{
    public function getName() : string
    {
        return 'Undefined sdl query type. ('.$this->getMessage().')';
    }
}