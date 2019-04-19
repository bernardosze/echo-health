<?php
/**
 * Default app login controller.
 *
 * @author: Leonardo Otoni
 */
namespace classes\controllers\publicControllers {

    use \classes\business\UserBO as UserBO;
    use \classes\models\UserModel as UserModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\AuthenticationException as AuthenticationException;
    use \classes\util\helpers\Application as Application;
    use \classes\util\SecurityFilter as SecurityFilter;

    class LoginController extends AppBaseController
    {
        private $userAuthenticationErrorMsg = null;
        private $email = "";

        //overrided property to not set intranet pages
        protected $isIntranet = false;

        public function __construct()
        {
            parent::__construct(
                null,
                ["views/security/login.html"],
                null,
                ["static/js/validation/login.js"]
            );

        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {

            if (SecurityFilter::isUserLogged() && !SecurityFilter::isExpiredSession()) {
                //User is already authenticated, so dispatch to the intranet home.
                header("Location: " . Application::getSetupConfig(Application::HOME_PAGE_INTRANET));
            }

            parent::doGet();
        }

        /**
         * Method override.
         * Process POST requests.
         */
        protected function doPost()
        {
            $userModel = new UserModel();
            $this->email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
            $userModel->setEmail($this->email);
            $userModel->setPassword(filter_input(INPUT_POST, "password", FILTER_SANITIZE_STRING));

            $json = [];
            try {
                $userBO = new UserBO();
                $userSessionData = $userBO->authenticateUser($userModel);
                session_start();
                $_SESSION[AppConstants::USER_SESSION_DATA] = serialize($userSessionData);
                $_SESSION[AppConstants::USER_LAST_ACTIVITY_TIME] = $_SERVER["REQUEST_TIME"];
                $homePageIntranet = Application::getSetupConfig(Application::HOME_PAGE_INTRANET);
                $json = ["status" => "ok", "message" => "Authenticated", "url" => $homePageIntranet];
            } catch (AuthenticationException $e) {
                //User could not be authenticated
                $json = ["status" => "error", "message" => AppConstants::USER_AUTHENTICATION_ERROR_MSG];
                //$this->userAuthenticationErrorMsg = AppConstants::USER_AUTHENTICATION_ERROR_MSG;
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
                exit();
            }
        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {
            $userAuthenticationErrorMsg = $this->userAuthenticationErrorMsg;
            $email = $this->email;

            foreach ($views as $view) {
                require_once $view;
            }
        }

        /**
         * Method override.
         * It defines that the controller must to render only the predefined pages.
         */
        protected function isIntranet()
        {
            return false;
        }

    }

    new LoginController();

}
