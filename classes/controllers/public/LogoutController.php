<?php
/**
 * Logout controller. It invalidate the user session and performs the redirection
 * to the application
 *
 * @author: Leonardo Otoni
 */
namespace classes\controllers\publicControllers {

    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\helpers\Application as Application;
    use \classes\util\SecurityFilter as SecurityFilter;

    class LogoutController extends AppBaseController
    {
        public function __construct()
        {
            SecurityFilter::invalidadeUserSession();
            header("Location: " . Application::getSetupConfig(Application::HOME_PAGE));
        }
    }

    new LogoutController();

}
