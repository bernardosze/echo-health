<?php
/**
 * Database Singleton Class
 * It manages the connection to the database using PDO
 *
 * Author: Leonardo Otoni
 */
namespace Database {

    use PDOException;
    use util\exceptions\FatalException as FatalException;
    use \PDO;

    class Database
    {
        private static $db;
        private static $instance = null;
        private const MYSQL_DOWN_ERROR_CODE = 2002;

        //TODO: Move the connection data to config class
        //Database connection attributes
        private const DB_DSN = 'mysql:host=localhost;dbname=php';
        private const DB_USERNAME = 'php';
        private const DB_PASSWORD = 'php';

        private function __construct()
        {
        }

        /**
         * Returns a PDO Connection to the Database
         */
        public static function getConnection()
        {
            if (!isset(self::$instance)) {
                self::$instance = new Database();
                self::$instance->connectToDatabase();
            }
            return self::$db;
        }

        /**
         * Open a connection to the specified database.
         */
        private static function connectToDatabase()
        {
            try {
                self::$db = new PDO(self::DB_DSN,
                    self::DB_USERNAME,
                    self::DB_PASSWORD,
                    array(
                        PDO::ATTR_PERSISTENT => true,
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    )
                );
            } catch (PDOException $e) {
                if ($e->getCode() == self::MYSQL_DOWN_ERROR_CODE) {
                    //database is unreachable
                    throw new FatalException($e->getMessage(), $e->getCode());
                } else {
                    throw $e;
                }
            }
        }
    }

}
