<?php

if (!function_exists('assertThrowException')) {
    function assertThrowException($expect, $closure) {
        try {
            $arguments = array_slice(func_get_args(), 2);
            call_user_func_array($closure, $arguments);
        } catch (Exception $e) {
            if ($e instanceof $expect) {
                return;
            }
            assertInstanceOf($expect, $e);
        }
        throw new Exception('例外が発生しなかった');
    }
}
