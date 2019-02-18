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
    use \util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \util\interfaces\ISecurityProfile as ISecurityProfile;
    use \util\UserSessionProfile as UserSessionProfile;

    class UserModel implements ISecurityProfile
    {

        private const USER_REGISTER_DATA_EXCEPTION = "Not all user data was provided to be inserted.";
        private const USER_REGISTER_AGE_EXCEPTION = "Date of birthday cannot be a future date.";
        private const USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION = "Informed email is already in use, please choose another one.";
        private const USER_AUTHENTICATION_EXCEPTION = "User data not provided.";
        private const INVALID_USER_PASSWORD_EXCEPTION = "Password not match.";
        private const USER_NOT_FOUND_EXCEPTION = "User not found into database.";
        private const UPDATE_USER_PASSWD_INVALID_ARGUMENTS = "Impossible to update user password. Data is missing.";
        private const UPDATE_USER_PASSWD_ERROR = "An error occurred trying to update the user's password: ";

        //Array Keys
        private const KEY_USER_ID = "id";
        private const KEY_USER_EMAIL = "email";
        private const KEY_FIRST_NAME = "first_name";
        private const KEY_PASSWD = "password";
        private const KEY_LAST_LOGIN = "last_login";
        private const KEY_LAST_LOGIN_ATTEMPT = "last_login_attempt";
        private const KEY_LOGIN_ATTEMPT = "login_attempt";
        private const KEY_USER_PROFILES = "userProfiles";

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

            $dmlInsertUser = "insert into USER (EMAIL, FIRST_NAME, LAST_NAME, PASSWORD, BIRTHDAY, BLOCKED, RECORD_CREATION) " .
                "values(:email, :firstName, :lastName, :password, :birthday, :blocked, :recordCreation )";

            $dmlSelectUserId = "select id from user where email= :email";

            $db = Database::getConnection();

            try {

                $db->beginTransaction();

                $statement = $db->prepare($dmlInsertUser);
                $statement->bindValue(":email", $email);
                $statement->bindValue(":firstName", $firstName);
                $statement->bindValue(":lastName", $lastName);
                $statement->bindValue(":password", $hash);
                $statement->bindValue(":birthday", date($birthday));
                $statement->bindValue(":blocked", "N");
                $statement->bindValue(":recordCreation", date("Y-m-d H:i:s"));
                $statement->execute();

                //Get the userId previously generated
                $statement = $db->prepare($dmlSelectUserId);
                $statement->bindValue(":email", $email);
                $statement->execute();
                $resultSet = $statement->fetch();
                $userId = $resultSet["id"];

                //Get the PATIENT Id profile
                $dmlPatientProfileId = "select id from profile where name= :name";
                $statement = $db->prepare($dmlPatientProfileId);
                $statement->bindValue(":name", ISecurityProfile::PATIENT);
                $statement->execute();
                $resultSet = $statement->fetch();
                $defaulProfileId = $resultSet["id"];

                //Register the user as a PATIENT by default
                $dmlInsertUserProfile = "insert into user_profile (USER_ID, PROFILE_ID) values (:userId, :profileId)";
                $statement = $db->prepare($dmlInsertUserProfile);
                $statement->bindValue(":userId", $userId);
                $statement->bindValue(":profileId", $defaulProfileId);
                $statement->execute();

                $db->commit();
            } catch (PDOException $e) {

                $db->rollBack();

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

            $userData = $this->getUserDataFromDB($email);
            $hashFromDB = $userData[self::KEY_PASSWD];
            $isAuthenticated = (isset($hashFromDB) && ($hashFromDB === $hash)) ? true : false;
            $this->registerLastLoginTime($userData, $isAuthenticated);

            if ($isAuthenticated) {

                $userSessionProfile = new UserSessionProfile(
                    $userData[self::KEY_USER_ID],
                    $userData[self::KEY_USER_EMAIL],
                    $userData[self::KEY_FIRST_NAME],
                    $userData[self::KEY_USER_PROFILES]
                );
                
                return $userSessionProfile;

            } else {
                throw new AuthenticationException(self::INVALID_USER_PASSWORD_EXCEPTION);
            }
        }

        /**
         * Get a user through a given email.
         */
        private function getUserDataFromDB($email)
        {
            $userData = "select id, email, first_name, password, last_login, last_login_attempt, login_attempt " .
                "from user where email = :email and blocked='N'";

            $userProfile = "select name " .
                "from profile p inner join user_profile up on p.id = up.profile_id " .
                "inner join user u on up.user_id = u.id " .
                "where email = :email";

            $db = Database::getConnection();

            try {

                $statement = $db->prepare($userData);
                $statement->bindValue(":email", $email);

                $statement->execute();
                if ($statement->rowCount() == 1) {
                    $resultSet = $statement->fetch();
                    $userArray = array(
                        self::KEY_USER_ID => $resultSet[self::KEY_USER_ID],
                        self::KEY_USER_EMAIL => $resultSet[self::KEY_USER_EMAIL],
                        self::KEY_FIRST_NAME => $resultSet[self::KEY_FIRST_NAME],
                        self::KEY_PASSWD => $resultSet[self::KEY_PASSWD],
                        self::KEY_LAST_LOGIN => $resultSet[self::KEY_LAST_LOGIN],
                        self::KEY_LAST_LOGIN_ATTEMPT => $resultSet[self::KEY_LAST_LOGIN_ATTEMPT],
                        self::KEY_LOGIN_ATTEMPT => $resultSet[self::KEY_LOGIN_ATTEMPT],
                    );

                    //Recover all user profiles
                    $statement = $db->prepare($userProfile);
                    $statement->bindValue(":email", $email);
                    $statement->execute();
                    $resultSet = $statement->fetchAll();

                    $profiles = array();
                    foreach ($resultSet as $profile) {
                        $profiles[] = $profile[0];
                    }

                    $userArray[self::KEY_USER_PROFILES] = $profiles;

                    return $userArray;

                } else {
                    throw new AuthenticationException(self::USER_NOT_FOUND_EXCEPTION);
                }

            } catch (PDOException $e) {
                throw $e;
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

        /**
         * Update the user password for a given userId
         */
        public function updateUserPassword($userId, $hash)
        {

            if (empty($userId) || empty($hash)) {
                throw new UpdateUserDataException(self::UPDATE_USER_PASSWD_INVALID_ARGUMENTS);
            }

            $updateQuery = "update user set password = :password where id = :userId";
            $db = Database::getConnection();

            try {
                $statement = $db->prepare($updateQuery);
                $statement->bindValue(":password", $hash);
                $statement->bindValue(":userId", $userId);
                $statement->execute();
            } catch (Exception $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_PASSWD_ERROR . $e->getMessage());
            } finally {
                $statement->closeCursor();
            }

        }

    }
}
