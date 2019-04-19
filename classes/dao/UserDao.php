<?php
/**
 * DAO Class to handle all User database operations
 * @author: Leonardo Otoni
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\models\UserModel as UserModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \classes\util\helpers\Application as Application;
    use \classes\util\UserSeachParams as UserSearchParams;

    class UserDao
    {

        private const USER_REGISTER_EMAIL_DUPLICATED_EXCEPTION = "Informed email is already in use, please choose another one.";
        private const UPDATE_USER_PASSWD_ERROR = "An error occurred trying to update the user's password: ";
        private const UPDATE_USER_EMAIL_ERROR = "An error occurred trying to update the user's email: ";
        private const UPDATE_USER_ERROR = "An error occurred trying to update the user: ";
        private const UPDATE_USER_ERROR_EMAIL = "Email already in use. Choose another.";

        public function __construct()
        {
        }

        public function getUserById($userId)
        {

            $query = "select * from users where id = :userId and blocked <> 'Y' LIMIT 1";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($query);
                $stmt->bindValue(":userId", $userId);
                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\UserModel");
                if ($stmt->rowCount() > 0) {
                    return $stmt->fetch();
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * Fetch a unique user by a given email.
         * It will return a UserModel object.
         * A NoDataFoundException can be thrown if no record fetched.
         */
        public function getUserByEmail($userEmail)
        {

            $query = "select * from users where email = :userEmail and blocked <> 'Y' LIMIT 1";

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

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * Fetch users using an UserSearchParams object. If it is null,
         * users will be fetched without any filter.
         * Returns an array of UserModel
         */
        public function getUserByUserSearchParams($userSearchParams)
        {
            if ($userSearchParams === null || $userSearchParams === "" ||
                !($userSearchParams instanceof \classes\util\UserSearchParams)) {
                throw new Exception("A UserSearchParams object must be provided");
            }

            $query = "select id, email, first_name, last_name from users ";
            $orderBy = " order by email asc ";
            $preparedParams = [];

            if (count($userSearchParams->toArray()) > 0) {

                $filterQuery = "";
                foreach ($userSearchParams->toArray() as $field => $value) {
                    $filterString = " lower(" . $field . ") like :" . $field . " ";
                    $filterQuery = $filterQuery . (empty($filterQuery) ? "where" . $filterString : "and" . $filterString);
                    $preparedParams[":" . $field] = $value;
                }

                $query = $query . $filterQuery;

            }

            $query = $query . $orderBy;

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);
                if (count($preparedParams) > 0) {
                    foreach ($preparedParams as $param => $value) {
                        $value = (strlen($value) > 1 ? strtolower($value) : $value);
                        $stmt->bindValue($param, $value . "%");
                    }
                }

                $stmt->execute();
                $stmt->setFetchMode(PDO::FETCH_CLASS, "\classes\models\UserModel");

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll();
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }
        }

        /**
         * Log a unsuccessful login attempt for a existed email.
         * If the number of attempts overcome the limit, the user will
         * be blocked into the database.
         */
        public function logUnsuccessfulLogin($userModel)
        {

            $query = "update users set " .
                "last_login_attempt = :lastLoginAttempt, " .
                "login_attempt = :loginAttempt, " .
                "blocked = :blocked " .
                "where id = :userId";

            $dbLoginAttempts = $userModel->getLoginAttempt();
            $loginAttempts = isset($dbLoginAttempts) ? $dbLoginAttempts + 1 : 1;
            $maxLoginAttempts = Application::getSetupConfig(Application::MAX_LOGIN_ATTEMPS);
            $blocked = ($loginAttempts < $maxLoginAttempts ? "N" : "Y");

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);

                $stmt->bindValue(":userId", $userModel->getId());
                $stmt->bindValue(":lastLoginAttempt", date("Y-m-d H:i:s"));
                $stmt->bindValue(":loginAttempt", $loginAttempts);
                $stmt->bindValue(":blocked", $blocked);

                $stmt->execute();

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * Log a successful login attempts.
         * It will set lasLoginAttempt (datetime) and loginAttempt (int) as null.
         */
        public function logSuccessfulLogin($userModel)
        {

            $query = "update users set last_login = :lastLogin, " .
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

            } finally {
                $stmt->closeCursor();
            }

        }

        /**
         * Register a new user and set him as a PATIENT Profile
         */
        public function insertNewUser($userModel)
        {

            $insertUserQuery = "insert into USERS (EMAIL, FIRST_NAME, LAST_NAME, PASSWORD, BIRTHDAY, BLOCKED, RECORD_CREATION) " .
                "values(:email, :firstName, :lastName, :password, :birthday, :blocked, :recordCreation )";

            $insertUserProfileQuery = "insert into user_profiles (user_id, profile_id) " .
                "select u.id as user_id, p.id as profile_id " .
                "from profiles p, users u " .
                "where p.name = :profileName and u.id = :userId";

            $db = Database::getConnection();

            try {

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
                $userModel->setId($db->lastInsertId());

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
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        /**
         * Change the user password in the database
         */
        public function updateUserPassword($userModel)
        {

            $updateQuery = "update users set password = :password where id = :userId";

            try {
                $db = Database::getConnection();

                $stmt = $db->prepare($updateQuery);

                $stmt->bindValue(":password", $userModel->getNewPassword());
                $stmt->bindValue(":userId", $userModel->getId());
                $stmt->execute();

            } catch (Exception $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_PASSWD_ERROR . $e->getMessage());
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }
        }

        public function updateUserEmail($userModel)
        {
            $updateQuery = "update users set email=:newEmail where email = :oldEmail";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($updateQuery);
                $stmt->bindValue(":newEmail", $userModel->getNewEmail());
                $stmt->bindValue(":oldEmail", $userModel->getEmail());
                $stmt->execute();

            } catch (PDOException $e) {
                if ($e->getCode() === "23000") {
                    //Integrity constraint violation
                    throw new UpdateUserDataException(self::UPDATE_USER_ERROR_EMAIL);
                } else {
                    throw new UpdateUserDataException(self::UPDATE_USER_EMAIL_ERROR . $e->getMessage());
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }
        }

        /**
         * Update the following user fields:
         * email, firstName, lastName, email, Birthday
         * $userModel - A provided UserModel Object
         */
        public function updateUser($userModel)
        {
            $updateQuery = "update users set first_name=:firstName, last_name=:lastName, email=:email, birthday=:birthday where id=:userId";

            try {

                $db = Database::getConnection();

                $stmt = $db->prepare($updateQuery);
                $stmt->bindValue(":email", $userModel->getEmail());
                $stmt->bindValue(":firstName", $userModel->getFirstName());
                $stmt->bindValue(":lastName", $userModel->getLastName());
                $stmt->bindValue(":birthday", $userModel->getBirthday());
                $stmt->bindValue(":userId", $userModel->getId());

                $stmt->execute();

            } catch (PDOException $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_ERROR . $e->getMessage());
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }
        }

    }

}
