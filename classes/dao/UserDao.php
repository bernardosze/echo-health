<?php

namespace classes\dao {

    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;
    use \Exception;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;

    class UserDao
    {

        private const USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION = "Informed email is already in use, please choose another one.";
        private const UPDATE_USER_PASSWD_ERROR = "An error occurred trying to update the user's password: ";
        private const UPDATE_USER_EMAIL_ERROR = "An error occurred trying to update the user's email: ";

        public function __construct()
        {
        }

        /**
         * Fetch a unique user by a given email.
         * It will return a UserModel object.
         * A NoDataFoundException can be thrown if no record fetched.
         */
        public function getUserByEmail($userEmail)
        {

            $query = "select * from user where email = :userEmail and blocked <> 'Y' LIMIT 1";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userEmail", $userEmail);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\UserModel");
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    throw new NoDataFoundException();
                }

            } catch (PDOException $e) {
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

        /**
         * Log a unsuccessful login attempt for a existed email.
         * If the number of attempts overcome the limit, the user will
         * be blocked into the database.
         */
        public function logUnsuccessfulLogin($userModel)
        {

            $query = "update user set " .
                "last_login_attempt = :lastLoginAttempt, " .
                "login_attempt = :loginAttempt, " .
                "blocked = :blocked " .
                "where id = :userId";

            $dbLoginAttempts = $userModel->getLoginAttempt();
            $loginAttempts = isset($dbLoginAttempts) ? $dbLoginAttempts + 1 : 1;
            $blocked = ($loginAttempts < AppConstants::MAX_LOGIN_ATTEMPS ? "N" : "Y");

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);

                $stmt->bindValue(":userId", $userModel->getId());
                $stmt->bindValue(":lastLoginAttempt", date("Y-m-d H:i:s"));
                $stmt->bindValue(":loginAttempt", $loginAttempts);
                $stmt->bindValue(":blocked", $blocked);

                $stmt->execute();

            } catch (PDOException $e) {
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

        /**
         * Log a successful login attempts.
         * It will set lasLoginAttempt (datetime) and loginAttempt (int) as null.
         */
        public function logSuccessfulLogin($userModel)
        {

            $query = "update user set last_login = :lastLogin, " .
                "last_login_attempt = :lastLoginAttempt, " .
                "login_attempt = :loginAttempt " .
                "where id = :userId";

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);

                $stmt->bindValue(":lastLogin", date("Y-m-d H:i:s"));
                $stmt->bindValue(":lastLoginAttempt", null);
                $stmt->bindValue(":loginAttempt", null);
                $stmt->bindValue(":userId", $userModel->getId());

                $stmt->execute();

            } catch (PDOException $e) {
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

        /**
         * Register a new user and set him as a PATIENT Profile
         */
        public function insertNewUser($userModel)
        {

            $insertUserQuery = "insert into USER (EMAIL, FIRST_NAME, LAST_NAME, PASSWORD, BIRTHDAY, BLOCKED, RECORD_CREATION) " .
                "values(:email, :firstName, :lastName, :password, :birthday, :blocked, :recordCreation )";

            $selectUserQuery = "select * from user where email= :email";

            $insertUserProfileQuery = "insert into user_profile (user_id, profile_id) " .
                "select u.id as user_id, p.id as profile_id " .
                "from profile p, user u " .
                "where p.name = :profileName and u.id = :userId";

            try {

                $db = Database::getConnection();

                /* It is necessary to guarantee that a User and UserProfile are saved
                 * in the same transaction. All users initially has a PATIENT profile
                 */
                $db->beginTransaction();

                $stmt = $db->prepare($insertUserQuery);
                $stmt->bindValue(":email", $userModel->getEmail());
                $stmt->bindValue(":firstName", $userModel->getFirstName());
                $stmt->bindValue(":lastName", $userModel->getLastName());
                $stmt->bindValue(":password", $userModel->getPassword());
                $stmt->bindValue(":birthday", date($userModel->getBirthday()));
                $stmt->bindValue(":blocked", "N");
                $stmt->bindValue(":recordCreation", date("Y-m-d H:i:s"));
                $stmt->execute();

                //Get the userId previously generated
                $stmt = $db->prepare($selectUserQuery);
                $stmt->bindValue(":email", $userModel->getEmail());
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\UserModel");
                $stmt->execute();
                //override the previous $userModel object.
                $userModel = $stmt->fetch();

                //Register the user as a PATIENT by default
                $stmt = $db->prepare($insertUserProfileQuery);
                $stmt->bindValue(":profileName", ISecurityProfile::PATIENT);
                $stmt->bindValue(":userId", $userModel->getId());
                $stmt->execute();

                $db->commit();

            } catch (PDOException $e) {

                $db->rollBack();

                if ($e->getCode() == 23000) {
                    //Email in duplicity
                    throw new RegisterUserException(self::USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION);
                } else {
                    throw $e;
                }

            } catch (Exception $e) {
                $db->rollBack();
                throw $e;
            } finally {
                $stmt->closeCursor();
            }

        }

        /**
         * Change the user password in the database
         */
        public function updateUserPassword($userModel)
        {

            $updateQuery = "update user set password = :password where id = :userId";

            try {
                $db = Database::getConnection();

                $statement = $db->prepare($updateQuery);

                $statement->bindValue(":password", $userModel->getNewPassword());
                $statement->bindValue(":userId", $userModel->getId());
                $statement->execute();

            } catch (Exception $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_PASSWD_ERROR . $e->getMessage());
            } finally {
                $statement->closeCursor();
            }
        }

        public function updateUserEmail($userModel)
        {
            $updateQuery = "update user set email=:newEmail, password=:newPassword where email = :oldEmail";

            try {

                $db = Database::getConnection();

                $statement = $db->prepare($updateQuery);
                $statement->bindValue(":newEmail", $userModel->getNewEmail());
                $statement->bindValue(":newPassword", $userModel->getNewPassword());
                $statement->bindValue(":oldEmail", $userModel->getEmail());
                $statement->execute();

            } catch (PDOException $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_EMAIL_ERROR . $e->getMessage());
            } finally {
                $statement->closeCursor();
            }
        }

    }

}
