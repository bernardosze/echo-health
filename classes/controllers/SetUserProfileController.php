<?php

namespace classes\controllers {

    use Exception;
    use \classes\business\ProfileBO as ProfileBO;
    use \classes\business\UserBO as UserBO;
    use \classes\models\ProfileModel as ProfileModel;
    use \classes\models\UserModel as UserModel;
    use \classes\util\base\AppBaseController as AppBaseController;

    /**
     * Controller Class for Set User Profile
     *
     * @author: Leonardo Otoni
     */
    class SetUserProfileController extends AppBaseController
    {

        private const DATA_SAVED = "Data successfully saved.";
        private const USER_NOT_FOUND = "Error loading user data";
        private const INVALID_REQUEST = "Invalid Request: User id not provided.";

        private $userId = "";
        private $firstName = "";
        private $lastName = "";
        private $email = "";
        private $birthday = "";
        private $appProfiles = "";
        private $userInEditProfiles = "";

        public function __construct()
        {
            parent::__construct(
                "Set User Profile",
                ["views/set_user_profile.html"],
                null,
                ["static/js/validation/set_user_profile.js"],
                true,
                true
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            parse_str($_SERVER['QUERY_STRING'], $qString);

            if (array_key_exists("id", $qString) &&
                !empty($qString["id"])) {

                try {

                    $userBO = new UserBO();
                    $userModel = $userBO->fetchUserById($qString["id"]);
                    $this->userId = $userModel->getId();
                    $this->firstName = $userModel->getFirstName();
                    $this->lastName = $userModel->getLastName();
                    $this->email = $userModel->getEmail();
                    $this->birthday = $userModel->getBirthday();

                    $profileBO = new ProfileBO();
                    $profilesArray = $profileBO->getSpecialProfiles($qString["id"]);
                    $this->appProfiles = $profilesArray[0];
                    $this->userInEditProfiles = $profilesArray[1];

                } catch (NoDataFoundException $e) {
                    parent::setAlertErrorMessage($e->getMessage());
                } catch (Exception $e) {
                    parent::setAlertErrorMessage(self::USER_NOT_FOUND);
                }

            } else {
                parent::setAlertErrorMessage(self::INVALID_REQUEST);
            }

            parent::doGet();

        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doPost()
        {

            $this->userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);
            $this->firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_STRING);
            $this->lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
            $this->email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $this->birthday = filter_input(INPUT_POST, "birthday", FILTER_SANITIZE_STRING);

            $userModel = new UserModel();
            $userModel->setId($this->userId);
            $userModel->setFirstName($this->firstName);
            $userModel->setLastName($this->lastName);
            $userModel->setEmail($this->email);
            $userModel->setBirthday($this->birthday);

            $profiles = filter_input(INPUT_POST, 'profile', FILTER_DEFAULT, FILTER_REQUIRE_ARRAY);
            $profileModelArray = [];
            if (!empty($profiles)) {
                foreach ($profiles as $key => $data) {
                    $profileModel = new ProfileModel();
                    $profileModel->setId($data["id"]);
                    $profileModel->setName($data["name"]);
                    array_push($profileModelArray, $profileModel);

                }
            }

            $json = [];
            try {
                $userBO = new UserBO();
                $userBO->setUserProfile($userModel, $profileModelArray);
                $json = ["status" => "ok", "message" => self::DATA_SAVED];
            } catch (Exception $e) {
                $json = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
            }

        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {
            //page scope variables
            $userId = $this->userId;
            $firstName = $this->firstName;
            $lastName = $this->lastName;
            $email = $this->email;
            $birthday = $this->birthday;
            $appProfiles = $this->appProfiles;
            $userInEditProfiles = $this->userInEditProfiles;

            foreach ($views as $view) {
                require_once $view;
            }

        }

    }

    new SetUserProfileController();

}
