<?php
$driver = 'sqlite'; /* default driver */
if ($driver == 'sqlite') {
    $configDatabase = array(
        'driver' => $driver,
        'database_name' => 'test.db',
        'host' => '',
        'user_name' => '',
        'password' => ''
    );
} elseif ($driver == 'mysql') {
    $configDatabase = array(
        'driver' => $driver,
        'database_name' => 'default_database',
        'host' => 'default_host',
        'user_name' => 'default_username',
        'password' => 'default_password'
    );
} elseif ($driver = 'sqlsrv') {
    $configDatabase = array(
        'driver' => $driver,
        'database_name' => 'default_database',
        'host' => 'default_host',
        'user_name' => 'default_username',
        'password' => 'default_password'
    );
} else {
    $configDatabase = array(
        'driver' => $driver,
        'database_name' => 'default_database',
        'host' => 'default_host',
        'user_name' => 'default_username',
        'password' => 'default_password'
    );
}
return $configDatabase;
?>