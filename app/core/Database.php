<?php
require_once __DIR__ . '/../../config/config.php';

class Database {
    public static function connect() {
        return new PDO(
            "mysql:host=".DB_HOST.";dbname=".DB_NAME,
            DB_USER,
            DB_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }
}

