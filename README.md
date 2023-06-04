## About WpTestbench
WpTestbench is a tool used to set up a WordPress test environment for PHPUnit testing, in order to test your plugin.

## Installation

Install testbench via composer

    composer require wpstarter/wp-testbench --dev

Install WordPress

    ./vendor/bin/wp-testbench-install <db-name> <db-user> <db-pass> [db-host] [wp-version] [skip-database-creation]

Update phpunit.xml

    <phpunit 
        ...
        bootstrap="./vendor/wpstarter/wp-testbench/bootstrap.php"
    
    >
    ...
    <php>
        <env name="WP_TESTBENCH_PLUGIN" value="your-plugin.php"/>
        ...
    </php>
    </phpunit>

That's all then you can run the PHPUnit test

    ./vendor/bin/phpunit



    