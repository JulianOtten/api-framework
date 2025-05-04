<?php

function dd(...$data)
{
    $trace = debug_backtrace();

    $line = $trace[0] ?? $trace[0];

    if (count($data) == 1) {
        $data = $data[0];
    }

    echo $line['file'] . ":" . $line['line'];
    echo "<pre>";
    print_r($data);
    echo"</pre>";
    die;
}

function d(...$data)
{
    // return dv(...$data);
    $trace = debug_backtrace();

    if (count($data) == 1) {
        $data = $data[0];
    }

    $line = $trace[0] ?? $trace[0];
    echo $line['file'] . ":" . $line['line'];
    echo "<pre>";
    print_r($data);
    echo"</pre>";
}

function dv(...$data)
{
    $trace = debug_backtrace();

    if (count($data) == 1) {
        $data = $data[0];
    }

    $line = $trace[1] ?? $trace[0];
    echo $line['file'] . ":" . $line['line'];
    echo "<pre>";
    var_dump($data);
    echo"</pre>";
}
