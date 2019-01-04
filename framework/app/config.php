<?php

//namespace App;

// $config = [
//     "env" => "dev"
// ];

class Config {
    const ENV = "dev";
    CONST DB = [
        "host" => "localhost",
        "port" => 3306,
        "driver" => "mysql",
        "dbname" => "users",
        "charset" => "utf8mb4",
        "user" => "root",
        "pass" => "",
    ];
}

?>