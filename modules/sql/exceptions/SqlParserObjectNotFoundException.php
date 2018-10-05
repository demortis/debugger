<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.09.18
 * Time: 14:01
 */

namespace debugger\modules\sql\exceptions;

class SqlParserObjectNotFoundException extends \RuntimeException
{
    public function getName() : string
    {
        return 'Query parser object not found';
    }
}