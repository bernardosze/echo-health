<?php

namespace classes\controllers\changePassword {

    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;
    use \routes\RoutesManager as RoutesManager;

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

            try {
                $userBO = new UserBO();
                $userBO->updateUserPassword($userModel);
                parent::setAlertSuccessMessage("Password successfully updated.");
            } catch (UpdateUserDataException $e) {
                //Set the error message to appear on screen
                parent::setAlertErrorMessage($e->getMessage());
            } catch (\Exception $e) {
                require_once RoutesManager::_500_CONTROLLER;
                exit();
            }

            parent::doPost();

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
    }

    new ChangeUserPasswordController();

}
