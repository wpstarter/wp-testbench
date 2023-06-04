<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package WpTestbench
 */

require_once __DIR__.'/includes/functions.php';

if(!$_tests_dir=wp_testbench_env('WP_TESTBENCH_DIR')) {
    $_tests_dir = __DIR__;
}

if ( ! file_exists("$_tests_dir/wordpress/wp-settings.php") || !file_exists("$_tests_dir/wp-tests-config.php") ) {
	echo "WpTestbench is not installed. Please run install command first." . PHP_EOL;
    echo "WpTestbench PATH: ".$_tests_dir.PHP_EOL;
    echo "Check https://github.com/wpstarter/wp-testbench for more info.".PHP_EOL;
    exit(1);
}else {
    $_testbench_working_dir=getcwd();
    if(file_exists("{$_tests_dir}/vendor/autoload.php")) {
        require_once "{$_tests_dir}/vendor/autoload.php";
    }
    //Working dir maybe changed reset it back
    if($_testbench_working_dir!=getcwd()){
        @chdir($_testbench_working_dir);
    }
    if($_tests_plugin=wp_testbench_env('WP_TESTBENCH_PLUGIN')){
        if(file_exists($_tests_plugin)) {
            tests_load_plugin($_tests_plugin);
        }else{
            echo "\033[31mPlugin file [$_tests_plugin] is not exits.\033[0m".PHP_EOL;
        }
    }else {
        echo "\033[31mNo plugin to test, please set WP_TESTBENCH_PLUGIN env variable.\033[0m".PHP_EOL;
    }

    // Start up the WP testing environment.
    require "{$_tests_dir}/includes/load-wp.php";
}
