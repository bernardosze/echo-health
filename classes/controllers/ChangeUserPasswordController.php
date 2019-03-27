<?php

namespace classes\controllers\changePassword {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\email\EmailSender as EmailSender;

    /**
     * Controller Class for User Change Password
     *
     * @author: Leonardo Otoni
     */
    class ChangeUserPasswordController extends AppBaseController
    {

        private $userId = "";
        private $userEmail = "";

        public function __construct()
        {
            parent::__construct(
                "User Password Change",
                ["views/change_password.html"],
                null,
                [
                    "static/js/sha1.min.js",
                    "static/js/security.js",
                    "static/js/validation/user_password_change.js",
                ]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $this->userId = $userSessionData->getUserId();
            $this->userEmail = $userSessionData->getEmail();

            parent::doGet();
        }

        /**
         * Method override.
         * Process POST requests.
         */
        protected function doPost()
        {

            $this->userId = filter_input(INPUT_POST, "userId", FILTER_SANITIZE_NUMBER_INT);
            $this->userEmail = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);

            $userModel = new UserModel();
            $userModel->setId($this->userId);
            $userModel->setEmail($this->userEmail);
            $userModel->setPassword(filter_input(INPUT_POST, "currentPassword", FILTER_SANITIZE_STRING));
            $userModel->setNewPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

            $json = [];
            try {
                $userBO = new UserBO();
                $userBO->updateUserPassword($userModel);
                $this->notifyPasswordChange();
                $json = ["status" => "ok", "message" => "Password successfully updated."];

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
            $userId = $this->userId;
            $userEmail = $this->userEmail;

            foreach ($views as $view) {
                require_once $view;
            }

        }

        /**
         * Send email to the user informing that the password was changed.
         */
        private function notifyPasswordChange()
        {

            try {

                $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
                $firstName = $userSessionProfile->getFirstName();

                //Load Template email
                $message = \file_get_contents('./static/email_template/changepasswd.html');
                $message = str_replace("%username%", $firstName, $message);
                $message = str_replace("%system_time%", date("Y-m-d h:i:s a"), $message);

                $emailSender = new EmailSender();
                $emailSender->sendSystemEmail($this->userEmail, "Password Changed", $message);

            } catch (\Exception $e1) {
                //TODO: generate a log to register that the email routine is not working
            }

        }
    }

    new ChangeUserPasswordController();

}
