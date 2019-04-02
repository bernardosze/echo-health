<?php
/**
 * Controller to dispatch the user registration form
 *
 * @author: Leonardo Otoni
 */

namespace classes\controllers\publicControllers {

    use Exception;
    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\RegisterUserException as RegisterUserException;
    use \classes\util\helpers\Application as Application;
    use \routes\RoutesManager as RoutesManager;

    class SignUpController extends AppBaseController
    {

        //page variables
        private $email = "";
        private $firstName = "";
        private $lastName = "";
        private $birthday = "";
        private $error_message = null;

        public function __construct()
        {
            parent::__construct(
                null,
                ["views/security/signup.html"]
            );
        }

        /**
         * Method override.
         * Process POST requests.
         */
        protected function doPost()
        {

            $this->firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_EMAIL);
            $this->lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
            $this->email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
            $this->birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);

            //form posted
            $userModel = new UserModel();
            $userModel->setEmail($this->email);
            $userModel->setFirstName($this->firstName);
            $userModel->setLastName($this->lastName);
            $userModel->setBirthday($this->birthday);
            $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

            try {
                $userBO = new UserBO();
                $userBO->registerUser($userModel);
                header("Location: " . Application::getSetupConfig(Application::HOME_PAGE));
            } catch (RegisterUserException $e) {
                $this->error_message = "Invalid Registration: " . $e->getMessage();
            } catch (Exception $e) {
                require_once RoutesManager::_500_CONTROLLER;
                exit();
            }

            parent::doPost();

        }

        /**
         * Method override.
         * Render Use case View providing Controller's scope variables
         */
        protected function renderViewPages($views)
        {

            $email = $this->email;
            $firstName = $this->firstName;
            $lastName = $this->lastName;
            $birthday = $this->birthday;
            $error_message = $this->error_message;
            $moduleName = Application::getSetupConfig(Application::MODULE_NAME);

            foreach ($views as $view) {
                require_once $view;
            }
        }

        /**
         * Method override.
         * It define that the controller must to render only the predefined pages.
         */
        protected function isIntranet()
        {
            return false;
        }

    }

    new SignUpController();

    // const SIGN_UP_VIEW = "views/security/signup.html";
    // $moduleName = AppConstants::MODULE_NAME;
    // $email = $firstName = $lastName = $birthday = "";

    // if ($_SERVER['REQUEST_METHOD'] == 'GET') {
    //     require_once SIGN_UP_VIEW;
    //     exit;
    // } else {

    //     $firstName = filter_input(INPUT_POST, "firstName", FILTER_SANITIZE_EMAIL);
    //     $lastName = filter_input(INPUT_POST, "lastName", FILTER_SANITIZE_STRING);
    //     $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_STRING);
    //     $birthday = filter_input(INPUT_POST, 'birthday', FILTER_SANITIZE_STRING);

    //     //form posted
    //     $userModel = new UserModel();
    //     $userModel->setEmail($email);
    //     $userModel->setFirstName($firstName);
    //     $userModel->setLastName($lastName);
    //     $userModel->setBirthday($birthday);
    //     $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

    //     try {
    //         $userBO = new UserBO();
    //         $userBO->registerUser($userModel);
    //         header("Location: login");
    //     } catch (RegisterUserException $e) {
    //         $error_message = "Invalid Registration: " . $e->getMessage();
    //         require_once SIGN_UP_VIEW;
    //     } catch (Exception $e) {
    //         require_once RoutesManager::_500_CONTROLLER;
    //         exit();
    //     }
    // }

}
