<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.09.18
 * Time: 13:58
 */

namespace debugger\core\exceptions;


class ViewNotFoundException extends \RuntimeException
{
    public function getName() : string
    {
        return 'View file not found';
    }
}