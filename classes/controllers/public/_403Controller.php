<?php

namespace classes\controllers\publicControllers {

    use \classes\util\helpers\Application as Application;

    //Metadata used by the _403.html file
    $moduleName = Application::getSetupConfig(Application::MODULE_NAME);
    $homePageIntranet = Application::getSetupConfig(Application::HOME_PAGE_INTRANET);
    $urlHomePage = $moduleName . $homePageIntranet;
    $backgroundImage = $moduleName . "static/img/404_background.jpg";
    require_once "views/errors/_403.html";

}
