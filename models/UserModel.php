<?php
/**
 * User Model
 * Author: Leonardo Otoni
 */
namespace models {

    use Exception;
    use PDOException;
    use \database\Database as Database;
    use \util\AppConstants as AppConstants;
    use \util\exceptions\AuthenticationException as AuthenticationException;
    use \util\exceptions\RegisterUserException as RegisterUserException;

    class UserModel
    {

        private const USER_REGISTER_DATA_EXCEPTION = "Not all user data was provided to be inserted.";
        private const USER_REGISTER_AGE_EXCEPTION = "Date of birthday cannot be a future date.";
        private const USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION = "Informed email is already in use, please choose another one.";
        private const USER_AUTHENTICATION_EXCEPTION = "User data not provided.";
        private const INVALID_USER_PASSWORD_EXCEPTION = "Password not match.";
        private const USER_NOT_FOUND_EXCEPTION = "User not found into database.";

        //Array Keys
        private const KEY_USER_ID = "id";
        private const KEY_FIRST_NAME = "firstName";
        private const KEY_PASSWD = "password";
        private const KEY_LAST_LOGIN = "lastLogin";
        private const KEY_LAST_LOGIN_ATTEMPT = "lastLoginAttempt";
        private const KEY_LOGIN_ATTEMPT = "loginAttempt";

        /**
         * Default constructor
         */
        public function __construct()
        {
        }

        /**
         * Save a new user into the USER table.
         * The database table has constraints to avoid email duplicity
         */
        public function registerUser($email, $firstName, $lastName, $hash, $birthday)
        {
            if (empty($email) || empty($firstName) || empty($lastName) || empty($hash) || empty($birthday)) {
                throw new RegisterUserException(self::USER_REGISTER_DATA_EXCEPTION);
            }

            //General business rules
            $this->validateUserDateOfBirth($birthday);

            $query = "insert into USER (EMAIL, FIRST_NAME, LAST_NAME, PASSWORD, BIRTHDAY, BLOCKED, RECORD_CREATION) " .
                "values(:email, :firstName, :lastName, :password, :birthday, :blocked, :recordCreation )";

            $db = Database::getConnection();

            try {

                $statement = $db->prepare($query);
                $statement->bindValue(":email", $email);
                $statement->bindValue(":firstName", $firstName);
                $statement->bindValue(":lastName", $lastName);
                $statement->bindValue(":password", $hash);
                $statement->bindValue(":birthday", date($birthday));
                $statement->bindValue(":blocked", "N");
                $statement->bindValue(":recordCreation", date("Y-m-d H:i:s"));

                $statement->execute();

            } catch (PDOException $e) {
                if ($e->getCode() == 23000) {
                    //Email in duplicity
                    throw new RegisterUserException(self::USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION);
                } else {
                    throw $e;
                }
            } finally {
                $statement->closeCursor();
            }

        }

        /**
         * A valid date cannot be a future date
         */
        private function validateUserDateOfBirth($birthday)
        {
            if (date("Y-m-d") < date("Y-m-d", strtotime($birthday))) {
                throw new RegisterUserException(self::USER_REGISTER_AGE_EXCEPTION);
            }
        }

        /**
         * Authenticate a user matched by the hash. If a user is valid, return a user data
         */
        public function authenticateUser($email, $hash)
        {
            if (empty($email) || empty($hash)) {
                throw new AuthenticationException(self::USER_AUTHENTICATION_EXCEPTION);
            }

            $userData = $this->getUserPasswordFromDB($email);
            $hashFromDB = $userData[self::KEY_PASSWD];
            $isAuthenticated = (isset($hashFromDB) && ($hashFromDB === $hash)) ? true : false;
            $this->registerLastLoginTime($userData, $isAuthenticated);

            if ($isAuthenticated) {

                //remove sensitive data before make the Object available
                unset($userData[self::KEY_PASSWD]);
                unset($userData[self::KEY_LAST_LOGIN]);
                unset($userData[self::KEY_LAST_LOGIN_ATTEMPT]);
                unset($userData[self::KEY_LOGIN_ATTEMPT]);

                return $userData;

            } else {
                throw new AuthenticationException(self::INVALID_USER_PASSWORD_EXCEPTION);
            }
        }

        /**
         * Get a user through a given email.
         */
        private function getUserPasswordFromDB($email)
        {
            $query = "select id, first_name, password, last_login, last_login_attempt, login_attempt " .
                "from user where email = :email and blocked='N'";

            $db = Database::getConnection();

            try {

                $statement = $db->prepare($query);
                $statement->bindValue(":email", $email);

                $statement->execute();
                if ($statement->rowCount() == 1) {
                    $resultSet = $statement->fetch();
                    $userArray = array(
                        self::KEY_USER_ID => $resultSet["id"],
                        self::KEY_FIRST_NAME => $resultSet["first_name"],
                        self::KEY_PASSWD => $resultSet["password"],
                        self::KEY_LAST_LOGIN => $resultSet["last_login"],
                        self::KEY_LAST_LOGIN_ATTEMPT => $resultSet["last_login_attempt"],
                        self::KEY_LOGIN_ATTEMPT => $resultSet["login_attempt"],
                    );
                    return $userArray;

                } else {
                    throw new AuthenticationException(self::USER_NOT_FOUND_EXCEPTION);
                }

            } catch (PDOException $e) {
                throw $e->getMessage();
            } finally {
                $statement->closeCursor();
            }

        }

        /**
         * Set the Login attempts for a given user.
         * If the User get authenticated, register login time, and clean past attemps
         * It will register the last attempt time as well count the attempts.
         */
        private function registerLastLoginTime($userData, $isAuthenticated)
        {

            $updateQuery = "update user set last_login = :lastLogin, " .
                "last_login_attempt = :lastLoginAttempt, " .
                "login_attempt = :loginAttempt, " .
                "blocked = :blocked " .
                "where id = :userId";

            $db = Database::getConnection();

            try {

                $statement = $db->prepare($updateQuery);

                $statement->bindValue(":userId", $userData[self::KEY_USER_ID]);

                if ($isAuthenticated) {
                    $statement->bindValue(":lastLogin", date("Y-m-d H:i:s"));
                    $statement->bindValue(":lastLoginAttempt", null);
                    $statement->bindValue(":loginAttempt", null);
                    $statement->bindValue(":blocked", "N");
                } else {
                    //log the last login unsuccessful login attempt
                    $statement->bindValue(":lastLogin", $userData[self::KEY_LAST_LOGIN]); //keep the original state
                    $statement->bindValue(":lastLoginAttempt", date("Y-m-d H:i:s"));

                    $loginAttempts = isset($userData[self::KEY_LOGIN_ATTEMPT]) ? $userData[self::KEY_LOGIN_ATTEMPT] + 1 : 1;
                    $blocked = ($loginAttempts < AppConstants::MAX_LOGIN_ATTEMPS ? "N" : "Y");

                    $statement->bindValue(":loginAttempt", $loginAttempts);
                    $statement->bindValue(":blocked", $blocked);

                }

                $statement->execute();

            } catch (PDOException $e) {
                throw $e;
            } catch (Exception $e) {
                throw $e;
            } finally {
                $statement->closeCursor();
            }

        }

    }
}
