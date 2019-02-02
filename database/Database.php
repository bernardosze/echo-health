<?php
/**
 * Database Singleton Class
 * It manages the connection to the database using PDO
 *
 * Author: Leonardo Otoni
 */
namespace Database {

    use Exception;
    use \PDO;

    class Database
    {
        private static $db;
        private static $instance = null;

        private function __construct()
        {
        }

        /**
         * Returns a PDO Connection to the Database
         */
        public static function getConnection()
        {
            if (self::$instance == null) {
                self::$instance = new Database();
                self::$instance->connectToDatabase();
            }
            return self::$db;
        }

        private static function connectToDatabase()
        {
            //TODO: Move the connection data to config class
            $db_dsn = 'mysql:host=localhost;dbname=php';
            $db_username = 'php';
            $db_password = 'php';

            try {
                self::$db = new PDO($db_dsn, $db_username, $db_password);
                self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (Exception $e) {
                throw $e;
            }
        }

    }

}
