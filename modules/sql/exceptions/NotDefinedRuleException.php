<?php
/**
 * Created by PhpStorm.
 * User: eugene
 * Date: 13.09.18
 * Time: 14:20
 */

namespace debugger\modules\sql\exceptions;


class NotDefinedRuleException extends \InvalidArgumentException
{
    public function getName() : string
    {
        return 'There is no rule for the specified query type. ('.$this->getMessage().')';
    }
}