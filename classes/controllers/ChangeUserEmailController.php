<?php
namespace classes\controllers\changeemail {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\UserSessionProfile as UserSessionProfile;

    /**
     * Controller class for User Change Email
     *
     * @author: Leonardo Otoni
     */
    class ChangeEmailController extends AppBaseController
    {

        //page scope variables
        private $currentEmail = null;
        private $newEmail = null;
        private const DATA_SAVED = "Email successfully updated.";

        public function __construct()
        {
            parent::__construct(
                "User Email Change",
                ["views/change_email.html"],
                null,
                [
                    "static/js/sha1.min.js",
                    "static/js/security.js",
                    "static/js/validation/user_email_change.js",
                ]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $this->userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $this->currentEmail = $this->userSessionData->getEmail();
            $this->newEmail = "";

            parent::doGet();

        }

        /**
         * Method override.
         * Process POST requests.
         */
        protected function doPost()
        {

            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $currentEmail = $userSessionData->getEmail();
            $userModel = new UserModel();

            //data to update the User Email
            $userModel->setEmail(filter_input(INPUT_POST, "currentEmail", FILTER_SANITIZE_EMAIL));
            $userModel->setNewEmail(filter_input(INPUT_POST, "newEmail", FILTER_SANITIZE_EMAIL));
            $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

            $json = [];
            try {

                $userBO = new UserBO();
                $userBO->updateUserEmail($userModel);

                //data to re-create a new UserSessionProfile
                $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $userSessionProfile = new UserSessionProfile(
                    $userSessionData->getUserId(),
                    $userModel->getNewEmail(),
                    $userSessionData->getFirstName(),
                    $userSessionData->getProfiles()
                );
                $_SESSION[AppConstants::USER_SESSION_DATA] = serialize($userSessionProfile);

                $json = ["status" => "ok", "message" => self::DATA_SAVED];

            } catch (Exception $e) {
                $json = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
            }

        }

        /**
         * Overrided funcion.
         * Render the view preserving the scope of variables required on page
         */
        protected function renderViewPages($views)
        {

            //Specific variables used on page scope
            $currentEmail = $this->currentEmail;
            $newEmail = $this->newEmail;

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new ChangeEmailController();

}
