<?php
if(!function_exists('wp_testbench_env')) {
    function wp_testbench_env($var)
    {
        if(!empty($_SERVER[$var])){
            return $_SERVER[$var];
        }
        if(!empty($_ENV[$var])){
            return $_ENV[$var];
        }
        return getenv($var);
    }
}
if(!function_exists('wp_testbench_putenv')) {
    function wp_testbench_putenv($var,$val)
    {
        $_SERVER[$var]=$val;
        $_ENV[$var]=$val;
        putenv("$var=$val");
    }
}
