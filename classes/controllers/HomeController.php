<?php
namespace classes\controllers {

    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;

    /**
     * App Home Page Controller
     *
     * @author: Leonardo Otoni
     */
    class HomeController extends AppBaseController
    {

        public function __construct()
        {
            parent::__construct(
                "Home Page",
                ["views/home.html"]
            );
        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new HomeController();

}
