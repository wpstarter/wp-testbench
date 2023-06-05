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
if(!function_exists('_wp_testbench_path_is_absolute')){
    function _wp_testbench_path_is_absolute( $path ) {
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
if(!function_exists('_wp_testbench_clean_path')){
    function _wp_testbench_clean_path($path) {
        // Split the path into individual directory components
        $components = preg_split('#[\\\\/]+#', $path);

        // Initialize an empty array to store the cleaned components
        $cleanedComponents = [];

        // Check if the path is an absolute path on Linux
        $isAbsolute = ($path[0] === '/');

        // Iterate through each component
        foreach ($components as $component) {
            // Skip empty components and the current directory (".")
            if ($component === '' || $component === '.') {
                continue;
            }

            // If the component is the parent directory (".."), remove the last cleaned component
            if ($component === '..') {
                // Only remove the last component if it is not an absolute path on Linux
                if ($isAbsolute && count($cleanedComponents) === 0) {
                    continue;
                }
                array_pop($cleanedComponents);
            } else {
                // Add the component to the cleaned components array
                $cleanedComponents[] = $component;
            }
        }

        // Reconstruct the cleaned path by joining the cleaned components with "/"
        $cleanedPath = implode(DIRECTORY_SEPARATOR, $cleanedComponents);

        // Prepend the leading slash for absolute paths on Linux
        if ($isAbsolute && !empty($cleanedPath)) {
            $cleanedPath = DIRECTORY_SEPARATOR . $cleanedPath;
        }

        return $cleanedPath;
    }
}
if(!function_exists('wp_testbench_path')){
    /**
     * Get absolute path from working dir to current path
     * @param $path
     * @return string
     */
    function wp_testbench_path($path=''){
        if(_wp_testbench_path_is_absolute($path)){
            return $path;
        }
        $currentDir=wp_testbench_env('WP_TESTBENCH_WORKING_DIR');
        if(!$currentDir){
            $currentDir=getcwd();
        }
        return _wp_testbench_clean_path(rtrim($currentDir,'\/').DIRECTORY_SEPARATOR.$path);
    }
}
