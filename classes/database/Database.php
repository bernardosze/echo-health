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
    use \classes\util\helpers\Application as Application;
    use \PDO;

    class Database
    {
        private static $db;
        private static $instance = null;
        private const MYSQL_DOWN_ERROR_CODE = 2002;

        //Database connection attributes
        private static $dbDSN;
        private static $dbUsername;
        private static $dbPassword;

        private function __construct()
        {
            //Load the Applicaton Setup file to get Database connection info
            self::$dbDSN = Application::getSetupConfig(Application::DB_DSN);
            self::$dbUsername = Application::getSetupConfig(Application::DB_USERNAME);
            self::$dbPassword = Application::getSetupConfig(Application::DB_PASSWORD);
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
                self::$db = new PDO(self::$dbDSN,
                    self::$dbUsername,
                    self::$dbPassword,
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