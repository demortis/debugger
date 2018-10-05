<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 20.09.18
 * Time: 10:41
 */

namespace debugger\core\exceptions;


class EmptyModuleCollectionException extends \RuntimeException
{
    public function getName()
    {
        return 'Debug modules collection is empty';
    }
}