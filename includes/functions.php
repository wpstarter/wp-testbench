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
if(!function_exists('wp_testbench_path_is_absolute')){
    function wp_testbench_path_is_absolute( $path ) {
        /*
         * This is definitive if true but fails if $path does not exist or contains
         * a symbolic link.
         */
        if ( realpath( $path ) === $path ) {
            return true;
        }

        if ( strlen( $path ) === 0 || '.' === $path[0] ) {
            return false;
        }

        // Windows allows absolute paths like this.
        if ( preg_match( '#^[a-zA-Z]:\\\\#', $path ) ) {
            return true;
        }

        // A path starting with / or \ is absolute; anything else is relative.
        return ( '/' === $path[0] || '\\' === $path[0] );
    }
}
if(!function_exists('wp_testbench_path')){
    /**
     * Get absolute path from working dir to current path
     * @param $path
     * @return string
     */
    function wp_testbench_path($path=''){
        if(wp_testbench_path_is_absolute($path)){
            return $path;
        }
        $currentDir=wp_testbench_env('WP_TESTBENCH_WORKING_DIR');
        if(!$currentDir){
            $currentDir=getcwd();
        }
        return realpath(rtrim($currentDir,'\/').DIRECTORY_SEPARATOR.$path);
    }
}
