<?php

function dd(...$data)
{
    $trace = debug_backtrace();

    $line = $trace[0] ?? $trace[0];
    echo $line['file'] . ":" . $line['line'];
    echo "<pre>";
    print_r($data);
    echo"</pre>";
    die;
}

function d(...$data)
{
    $trace = debug_backtrace();

    $line = $trace[1] ?? $trace[0];
    echo $line['file'] . ":" . $line['line'];
    echo "<pre>";
    print_r($data);
    echo"</pre>";
}
