<?php

namespace App\Logging;

use Psr\Log\AbstractLogger as LogAbstractLogger;

abstract class AbstractLogger extends LogAbstractLogger
{
    
    protected function interpolate($message, array $context = array())
    {
        // build a replacement array with braces around the context keys
        $replace = array();
        foreach ($context as $key => $val) {
            // check that the value can be cast to string
            if (!is_array($val) && (!is_object($val) || method_exists($val, '__toString'))) {
                $replace['{' . $key . '}'] = $val;
            }
        }
    
        // interpolate replacement values into the message and return
        return strtr($message, $replace);
    }
}