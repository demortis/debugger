<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 11:10
 */

namespace debugger\core\exceptions;


class EmptyArrayOfModulesException extends \RuntimeException
{
    public function getName()
    {
        return 'Empty array of modules given';
    }
}