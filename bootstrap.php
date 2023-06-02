<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Hello
 */

if(!$_tests_dir=getenv( 'WP_TESTBENCH_DIR')) {
    $_tests_dirs = [__DIR__, rtrim(sys_get_temp_dir(), '/\\') . '/wp-testbench'];
    $_tests_dir = __DIR__;
    foreach ($_tests_dirs as $_tests_dir_tmp) {
        if (file_exists("$_tests_dir_tmp/wordpress/wp-settings.php")
            && file_exists("$_tests_dir_tmp/wp-tests-config.php")
        ) {
            $_tests_dir = $_tests_dir_tmp;
        }
    }
    putenv("WP_TESTBENCH_DIR=$_tests_dir");
}

if ( ! file_exists("$_tests_dir/wordpress/wp-settings.php") || !file_exists("$_tests_dir/wp-tests-config.php") ) {
	echo "Test bench not installed in [$_tests_dir]. Please run install command first." . PHP_EOL;
    exit(1);
}else {

    if(file_exists("{$_tests_dir}/vendor/autoload.php")) {
        //Test bench from standalone location
        require_once "{$_tests_dir}/vendor/autoload.php";
    }

    $_tests_plugin=getenv('WP_TESTBENCH_PLUGIN');
    if(!$_tests_plugin && defined('WP_TESTBENCH_PLUGIN')){
        $_tests_plugin=WP_TESTBENCH_PLUGIN;
    }
    if($_tests_plugin){
        tests_load_plugin($_tests_plugin);
    }else {
        echo "No plugin to test, please set WP_TESTBENCH_PLUGIN env variable";
    }

    // Start up the WP testing environment.
    require "{$_tests_dir}/load-wp.php";
}
