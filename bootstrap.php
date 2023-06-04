<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package WpTestbench
 */

if(!$_tests_dir=getenv( 'WP_TESTBENCH_DIR')) {
    $_tests_dir = __DIR__;
    putenv("WP_TESTBENCH_DIR=$_tests_dir");
}

if ( ! file_exists("$_tests_dir/wordpress/wp-settings.php") || !file_exists("$_tests_dir/wp-tests-config.php") ) {
	echo "WpTestbench is not installed. Please run install command first." . PHP_EOL;
    echo "WpTestbench PATH: ".$_tests_dir.PHP_EOL;
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
    $_tests_plugin=getenv('WP_TESTBENCH_PLUGIN');
    if(!$_tests_plugin && defined('WP_TESTBENCH_PLUGIN')){
        $_tests_plugin=WP_TESTBENCH_PLUGIN;
    }
    if($_tests_plugin){
        if(file_exists($_tests_plugin)) {
            tests_load_plugin($_tests_plugin);
        }else{
            echo "\033[31mPlugin file [$_tests_plugin] is not exits.\033[0m".PHP_EOL;
        }
    }else {
        echo "\033[31mNo plugin to test, please set WP_TESTBENCH_PLUGIN env variable.\033[0m".PHP_EOL;
    }

    // Start up the WP testing environment.
    require "{$_tests_dir}/load-wp.php";
}
