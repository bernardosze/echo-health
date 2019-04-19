<?php

namespace classes\controllers\publicControllers {

    use \classes\util\helpers\Application as Application;

    //Metadata used by the _404.html file
    $moduleName = Application::getSetupConfig(Application::MODULE_NAME);
    $homePage = Application::getSetupConfig(Application::HOME_PAGE);
    $urlHomePage = $moduleName . $homePage;
    $backgroundImage = $moduleName . "static/img/404_background.jpg";
    require_once "views/errors/_404.html";

}
