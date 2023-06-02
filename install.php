<?php
/**
 * Installs WordPress for the purpose of the unit-tests
 *
 * @todo Reuse the init/load code in init.php
 */
error_reporting( E_ALL & ~E_DEPRECATED & ~E_STRICT );
$multisite        = in_array( 'run_ms_tests', $argv, true );
define( 'WP_INSTALLING', true );

/*
 * Cron tries to make an HTTP request to the site, which always fails,
 * because tests are run in CLI mode only.
 */
define( 'DISABLE_WP_CRON', true );

require_once __DIR__.'/load-wp.php';

require_once ABSPATH . 'wp-admin/includes/upgrade.php';
require_once ABSPATH . 'wp-includes/class-wpdb.php';

/*
 * default_storage_engine and storage_engine are the same option, but storage_engine
 * was deprecated in MySQL (and MariaDB) 5.5.3, and removed in 5.7.
 */
if ( version_compare( $wpdb->db_version(), '5.5.3', '>=' ) ) {
    $wpdb->query( 'SET default_storage_engine = InnoDB' );
} else {
    $wpdb->query( 'SET storage_engine = InnoDB' );
}
$wpdb->select( DB_NAME, $wpdb->dbh );

echo 'Installing...' . PHP_EOL;


$wpdb->query( 'SET foreign_key_checks = 0' );
foreach ( $wpdb->tables() as $table => $prefixed_table ) {
    //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $wpdb->query( "DROP TABLE IF EXISTS $prefixed_table" );
}

foreach ( $wpdb->tables( 'ms_global' ) as $table => $prefixed_table ) {
    //phpcs:ignore WordPress.DB.PreparedSQL.InterpolatedNotPrepared
    $wpdb->query( "DROP TABLE IF EXISTS $prefixed_table" );

    // We need to create references to ms global tables.
    if ( $multisite ) {
        $wpdb->$table = $prefixed_table;
    }
}
$wpdb->query( 'SET foreign_key_checks = 1' );
function _set_default_permalink_structure_for_tests() {
    update_option( 'permalink_structure', '/%year%/%monthnum%/%day%/%postname%/' );
}
// Prefill a permalink structure so that WP doesn't try to determine one itself.
add_action( 'populate_options', '_set_default_permalink_structure_for_tests' );

wp_install( WP_TESTS_TITLE, 'admin', WP_TESTS_EMAIL, true, null, 'password' );

// Delete dummy permalink structure, as prefilled above.
if ( ! is_multisite() ) {
    delete_option( 'permalink_structure' );
}
remove_action( 'populate_options', '_set_default_permalink_structure_for_tests' );

if ( $multisite ) {
    echo 'Installing network...' . PHP_EOL;

    define( 'WP_INSTALLING_NETWORK', true );

    $title             = WP_TESTS_TITLE . ' Network';
    $subdomain_install = false;

    install_network();
    $error = populate_network( 1, WP_TESTS_DOMAIN, WP_TESTS_EMAIL, $title, '/', $subdomain_install );

    if ( is_wp_error( $error ) ) {
        wp_die( $error );
    }

    $wp_rewrite->set_permalink_structure( '' );
}
