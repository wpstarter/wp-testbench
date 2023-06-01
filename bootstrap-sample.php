<?php
/**
 * PHPUnit bootstrap file.
 *
 * @package Hello
 */

$_tests_dir = getenv( 'WP_TESTS_DIR' );

if ( ! $_tests_dir ) {
	$_tests_dir = rtrim( sys_get_temp_dir(), '/\\' ) . '/wp-testbench';
}

if ( ! file_exists( "{$_tests_dir}/vendor/autoload.php" ) ) {
	echo "Could not find {$_tests_dir}/vendor/autoload.php, have you run bin/install-wp-tests.sh ?" . PHP_EOL;
    echo "Warn: use current WordPress instance for testing!!!";
    // Load WpStarter autoload file
    require dirname(__DIR__).'/bootstrap/autoload.php';
}else {

    // Give access to tests_load_plugin() function.
    require_once "{$_tests_dir}/vendor/autoload.php";

    // Register plugin to load
    tests_load_plugin(dirname(__DIR__).'/main.php');

    // Start up the WP testing environment.
    require "{$_tests_dir}/bootstrap.php";
}
