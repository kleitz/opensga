<?php

include 'Config/database.php';

$db = new DATABASE_CONFIG();

return array(
    "paths" => array(
        "migrations" => "Config/migrations"
    ),
    "environments" => array(
        "default_migration_table" => "schema_migrations",
        "default_database" => "opensga2",
        "dev" => array(
            "adapter" => "mysql",
            "host" => $db->default['host'],
            "name" => $db->default['database'],
            "user" => $db->default['login'],
            "pass" => $db->default['password'],
            //"port" => $db->default['port']
        )
    )
);
?>