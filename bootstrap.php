<?php
/**
 * Installs WordPress for running the tests and loads WordPress and the test libraries
 */

if ( defined( 'WP_TESTS_CONFIG_FILE_PATH' ) ) {
    $config_file_path = WP_TESTS_CONFIG_FILE_PATH;
} else {
    $config_file_path =  __DIR__;
    if ( ! file_exists( $config_file_path . '/wp-tests-config.php' ) ) {
        // Support the config file from the root of the develop repository.
        if ( basename( $config_file_path ) === 'phpunit' && basename( dirname( $config_file_path ) ) === 'tests' ) {
            $config_file_path = dirname( dirname( $config_file_path ) );
        }
    }
    $config_file_path .= '/wp-tests-config.php';
}


if ( ! is_readable( $config_file_path ) ) {
    echo 'Error: wp-tests-config.php is missing! Please use wp-tests-config-sample.php to create a config file.' . PHP_EOL;
    exit( 1 );
}

require_once $config_file_path;

// Load WordPress.
if(!defined('ABSPATH')){
    echo 'Error: no ABSPATH';
    exit(1);
}
require_once ABSPATH . 'wp-settings.php';


