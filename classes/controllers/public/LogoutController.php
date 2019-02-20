<?php
/**
 * Logout controller. It invalidate the user session and performs the redirection
 * to the application
 *
 * Author: Leonardo Otoni
 */
namespace classes\controllers\publicControllers {

    use \classes\util\AppConstants as AppConstants;
    use \classes\util\SecurityFilter as SecurityFilter;

    SecurityFilter::invalidadeUserSession();
    header("Location: " . AppConstants::HOME_PAGE);

}
