<?php
/**
 * Database Singleton Class
 * It manages the connection to the database using PDO
 *
 * @author: Leonardo Otoni
 */
namespace classes\Database {

    use PDOException;
    use \classes\util\exceptions\FatalException as FatalException;
    use \PDO;

    class Database
    {
        private static $db;
        private static $instance = null;
        private const MYSQL_DOWN_ERROR_CODE = 2002;

        //TODO: Move the connection data to config class
        //Database connection attributes
        private const DB_DSN = 'mysql:host=localhost:3306;dbname=php';

        private const DB_USERNAME='root';
        private const DB_PASSWORD='';

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
                        PDO::ATTR_CASE => PDO::CASE_LOWER,
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
