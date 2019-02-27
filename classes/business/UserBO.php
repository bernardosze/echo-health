<?php
/**
 * User Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use Exception;
    use \classes\dao\ProfileDao as ProfileDao;
    use \classes\dao\UserDao as UserDao;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\AuthenticationException as AuthenticationException;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \classes\util\UserSessionProfile as UserSessionProfile;
    use \classes\util\interfaces\ISecurityProfile as ISecurityProfile;
    use \classes\models\UserProfileModel as UserProfileModel;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class UserBO
    {

        private const USER_REGISTER_DATA_EXCEPTION = "Not all user data was provided to be inserted.";
        private const USER_REGISTER_AGE_EXCEPTION = "Date of birthday cannot be a future date.";

        private const USER_AUTHENTICATION_EXCEPTION = "User data not provided.";
        private const INVALID_USER_PASSWORD_EXCEPTION = "Operation not allowed: Wrong password.";
        private const USER_NOT_FOUND_EXCEPTION = "User not found into database.";

        private const UPDATE_USER_DATA_INVALID_ARGUMENTS = "Impossible to update data. Data is missing.";

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
        public function registerUser($userModel)
        {

            $isIncompleteRecord = $userModel->hasEmptyFields();
            if ($isIncompleteRecord) {
                throw new RegisterUserException(self::USER_REGISTER_DATA_EXCEPTION);
            }

            //General business rules
            $this->validateUserDateOfBirth($userModel->getBirthday());

            $userDao = new UserDao();
            $userDao->insertNewUser($userModel);

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
         * Authenticate a user matched by the hash. If a user is valid, return a
         * UserSessionProfile object.
         */
        public function authenticateUser($userModelFromForm)
        {

            if (empty($userModelFromForm->getEmail()) || empty($userModelFromForm->getPassword())) {
                throw new AuthenticationException(self::USER_AUTHENTICATION_EXCEPTION);
            }

            $userDao = new UserDao();

            try {
                $userModel = $userDao->getUserByEmail($userModelFromForm->getEmail());
            } catch (Exception $e) {
                //User not found
                throw new AuthenticationException(self::USER_AUTHENTICATION_EXCEPTION);
            }

            if ($userModelFromForm->getPassWord() !== $userModel->getPassword()) {
                //invalid password. It must to log the attempt
                $userDao->logUnsuccessfulLogin($userModel);
                throw new AuthenticationException(self::INVALID_USER_PASSWORD_EXCEPTION);
            } else {
                //log the successful attempt
                $userDao->logSuccessfulLogin($userModel);
                $profileDao = new ProfileDao();
                
                $profileModelArray;
                try{
                    $profileModelArray = $profileDao->getProfilesByUserId($userModel->getId());
                } catch (NoDataFoundException $e){
                    $profileModelArray = [];
                }
                
                return $this->createUserSessionProfile($userModel, $profileModelArray);
            }

        }

        /**
         * Based on the UseModel and ProfileModel[] Objects, create a UserSessionProfile
         * Object to be used by the Controllers.
         */
        private function createUserSessionProfile($userModel, $profileModelArray)
        {
            $profiles = [];
            foreach ($profileModelArray as $profileModel) {
                array_push($profiles, $profileModel->getName());
            }
            return new UserSessionProfile(
                $userModel->getId(),
                $userModel->getEmail(),
                $userModel->getFirstName(),
                $profiles
            );
        }

        /**
         * Update the user password for a given userId
         */
        public function updateUserPassword($userModel)
        {

            $userDao = new UserDao();
            $userModelFromDB = $userDao->getUserByEmail($userModel->getEmail());

            if (empty($userModel->getId()) ||
                $userModel->arePasswordsBlank() ||
                $userModel->arePasswordsEqual()) {
                throw new UpdateUserDataException(self::UPDATE_USER_DATA_INVALID_ARGUMENTS);
            } else if (($userModelFromDB->getPassword() !== $userModel->getPassword())) {
                throw new UpdateUserDataException(self::INVALID_USER_PASSWORD_EXCEPTION);
            } else {
                $userDao->updateUserPassword($userModel);
            }

        }

        /**
         * Update the user password for a given userId
         */
        public function updateUserEmail($userModel)
        {

            if (empty($userModel->getEmail()) ||
                empty($userModel->getNewEmail()) ||
                empty($userModel->getPassword()) ||
                ($userModel->getEmail() === $userModel->getNewEmail())) {
                throw new UpdateUserDataException(self::UPDATE_USER_DATA_INVALID_ARGUMENTS);
            }

            $userDao = new UserDao();
            $userModelFromDB = $userDao->getUserByEmail($userModel->getEmail());

            if ($userModelFromDB->getPassword() !== $userModel->getPassword()) {
                throw new UpdateUserDataException(self::INVALID_USER_PASSWORD_EXCEPTION);
            } else {
                $userDao->updateUserEmail($userModel);
            }

        }

        /**
         * Fetch used based on UserSearchParams object.
         * Return an array of UserModel
         */
        public function fetchUsers($userSearchParams)
        {
            $userDao = new UserDao();
            return $userDao->getUserByUserSearchParams($userSearchParams);
        }

        public function fetchUserById($userId)
        {
            $userDao = new UserDao();
            return $userDao->getUserById($userId);
        }

        /**
         * From a given $userModel object, check if exists changes. If yes, update the object
         * $userModel - UserModel object
         */
        public function updateUser($userModel)
        {
            if (empty($userModel) ||
                !($userModel instanceof \classes\models\UserModel) ||
                $userModel->isNotValidForUpdate()) {
                throw new UpdateUserDataException(self::UPDATE_USER_DATA_INVALID_ARGUMENTS);
            }

            $userDao = new UserDao();
            $userModelFromDB = $userDao->getUserById($userModel->getId());

            if ($userModel->getFirstName() !== $userModelFromDB->getFirstName() ||
                $userModel->getLastName() !== $userModelFromDB->getLastName() ||
                $userModel->getEmail() !== $userModelFromDB->getEmail() ||
                $userModel->getBirthday() !== $userModelFromDB->getBirthday()) {
                    $userDao->updateUser($userModel);
            }

        }

        /**
         * For a given userId and ProfileModel array, define wich operations must to be performed
         * and dispath them to the database in order to up-to-date the user profile
         */
        public function updateUserProfile($userId, $profileModelArray)
        {
            if(empty($userId)){
                throw new UpdateUserDataException(self::UPDATE_USER_DATA_INVALID_ARGUMENTS);
            }

            $profileDao = new ProfileDao();
            $profileModelArrayFromDB;
            try{
                $profileModelArrayFromDB = $profileDao->getProfilesByUserId($userId, ISecurityProfile::PATIENT);
            } catch (NoDataFoundException $e) {
                $profileModelArrayFromDB = [];
            }
            
            //check if needs to set a new profile for the  user
            $profilesToInsert = [];
            foreach ($profileModelArray as $profileModel) {
                $mustToInsert = true;
                foreach ($profileModelArrayFromDB as $profileModelFromDB) {
                    if($profileModel->getId() === $profileModelFromDB->getId()){
                        $mustToInsert = false;
                        break;
                    }
                }
                if($mustToInsert){
                    $upm = new UserProfileModel();
                    $upm->setUserId($userId);
                    $upm->setProfileId($profileModel->getId());
                    $profilesToInsert[] = $upm;
                }
            }

            $profilesToDelete = [];
            foreach ($profileModelArrayFromDB as $profileModelFromDB) {
                $mustToDelete = true;
                foreach ($profileModelArray as $profileModel) {
                    if($profileModelFromDB->getId() === $profileModel->getId()){
                        $mustToDelete = false;
                        break;
                    }
                }
                if($mustToDelete){
                    $upm = new UserProfileModel();
                    $upm->setUserId($userId);
                    $upm->setProfileId($profileModelFromDB->getId());
                    $profilesToDelete[] = $upm;
                }
            }

            if(count($profilesToInsert) > 0){
                $profileDao->insertUserProfile($profilesToInsert);
            }

            if(count($profilesToDelete) > 0){
                $profileDao->deleteUserProfile($profilesToDelete);
            }
            
        }

    }
}
