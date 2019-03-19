<?php

namespace classes\util\base {

    use Exception;
    use \classes\util\base\AbstractBaseController as AbstractBaseController;
    use \classes\util\MenuManager as MenuManager;
    use \routes\ApplicationRoutes as ApplicationRoutes;

    /**
     * Application Base Controller.
     *
     * It implements methods defined from the Ancestors to process requests.
     * Main Class to be inherit by Application's Controllers.
     *
     * @author: Leonardo Otoni.
     */
    class AppBaseController extends AbstractBaseController
    {

        //properties existing in the templates (header and footer)

        /**
         * The Page Title handled set  by the Controller
         *
         * @var string
         */
        private $pageTitle;

        /**
         * An array of additional JS files to be used by the view
         *
         * @var array[string]
         */
        private $extraJS;

        /**
         * An array of additional CCS files to be used by the view
         *
         * @var array[string]
         */
        private $extraCSS;

        /**
         * An array of additional HTML files to be rendered by the Controller
         *
         * @var array[string]
         */
        private $viewPages;

        /**
         * Variable used by the Header Template to exhibit Error Messages
         *
         * @var string
         */
        private $alertErrorMessage;

        /**
         * Mutator for AlertErrorMessage
         *
         * @param string
         */
        protected function setAlertErrorMessage($value)
        {
            $this->alertErrorMessage = $value;
        }

        /**
         * Variable used by the Header Template to exhibit Success Messages
         *
         * @var string
         */
        private $alertSuccessMessage;

        /**
         * Mutator for AlertSuccessMessage
         *
         * @param string
         */
        protected function setAlertSuccessMessage($value)
        {
            $this->alertSuccessMessage = $value;
        }

        /**
         * Variable used by the Header Template to exhibit Warning Messages
         *
         * @var string
         */
        private $alertWarningMessage;

        /**
         * Mutator for AlertWarningMessage
         *
         * @param string
         */
        protected function setAlertWarningMessage($value)
        {
            $this->alertWarningMessage = $value;
        }

        /**
         * Flag to enable loading the CSS and JS of flatPickrComponent;
         *
         * @var boolean
         */
        private $flatpickrComponent;

        /**
         * Flag to enable loading the CSS and JS of Boostrap-table component;
         *
         * @var boolean
         */
        private $bootstrapTableComponent;

        /**
         * Default constructor
         *
         * @param string $pageTitle
         * @param array[string] $viewPages
         * @param array[string] $extraCSS
         * @param array[string] $extraJS
         * @param bollean $flatpickrComponent
         * @param bollean $bootstrapTableComponent
         */
        protected function __construct(
            $pageTitle = null,
            $viewPages = null,
            $extraCSS = null,
            $extraJS = null,
            $flatpickrComponent = false,
            $bootstrapTableComponent = false) {

            $this->pageTitle = $pageTitle;
            $this->viewPages = $viewPages;
            $this->extraCSS = $extraCSS;
            $this->extraJS = $extraJS;
            $this->flatpickrComponent = $flatpickrComponent;
            $this->bootstrapTableComponent = $bootstrapTableComponent;

            $this->processRequest();
        }

        /**
         * Request Processor Function
         *
         * Any exception not handled by a SubClass will redirect to a friendly http 500 error.
         */
        public function processRequest()
        {
            try {
                if ($_SERVER['REQUEST_METHOD'] == 'GET') {
                    $this->doGet();
                } else {
                    $this->doPost();
                }
            } catch (\Exception $e) {
                //TODO: improve exception handling to avoid a generic error message.
                http_response_code(500);
                require_once ApplicationRoutes::_500_CONTROLLER;
                exit();
            }

        }

        /**
         * GET Request Processor.
         * It Must to be overrided to process additional logic and invoked to render the Views.
         */
        protected function doGet()
        {
            $this->setViewsOnResponse();
        }

        /**
         * POST Request Processor.
         * It Must to be overrided to process additional logic and invoked to render the Views.
         */
        protected function doPost()
        {
            $this->setViewsOnResponse();
        }

        /**
         * Render Controller Specific View Pages.
         *
         * This function must be overrided in cases where views are refering to specific scope variables from
         * the Controllers.
         *
         * @param array[string]
         */
        protected function renderViewPages($views)
        {
            foreach ($views as $view) {
                require_once $view;
            }
        }

        /**
         * Set the Predefined views on the response
         */
        private function setViewsOnResponse()
        {
            if ($this->isIntranet()) {
                $this->setIntranetViews();
            } else {
                $this->setExternalViews();
            }
        }

        /**
         * Set default pages on response.
         */
        private function setIntranetViews()
        {

            //header and footer scope variables
            $pageTitle = $this->pageTitle;
            $extraJS = $this->extraJS;
            $extraCSS = $this->extraCSS;
            $flatpickrComponent = $this->flatpickrComponent;
            $bootstrapTableComponent = $this->bootstrapTableComponent;
            $alertErrorMessage = $this->alertErrorMessage;
            $alertSuccessMessage = $this->alertSuccessMessage;
            $alertWarningMessage = $this->alertWarningMessage;

            //Filter the Main Menu according to the User Profiles
            $appMenu = MenuManager::getFiltredMenus();

            require_once parent::TEMPLATE_HEADER;

            if (isset($this->viewPages)) {
                $this->renderViewPages($this->viewPages);
            }

            require_once parent::TEMPLATE_FOOTER;

        }

        /**
         * Set only specific controller pages on response
         */
        private function setExternalViews()
        {
            $this->renderViewPages($this->viewPages);
        }

        /**
         * Flag function to define if the controller must render basic templates for intranet
         * environment, like header and footer pages.
         *
         * @return boolean
         */
        protected function isIntranet()
        {
            return true;
        }
    }
}
