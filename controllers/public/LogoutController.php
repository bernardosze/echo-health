<?php
/**
 * Logout controller. It invalidate the user session and performs the redirection
 * to the application
 *
 * Author: Leonardo Otoni
 */
namespace controllers\publicControllers {

    use \util\AppConstants as AppConstants;
    use \util\SecurityFilter as SecurityFilter;

    SecurityFilter::invalidadeUserSession();
    header("Location: " . AppConstants::HOME_PAGE);

}
