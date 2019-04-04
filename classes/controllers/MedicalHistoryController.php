<?php
namespace classes\controllers {

    use \classes\business\TreatPatientBO as TreatPatientBO;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class MedicalHistoryController extends AppBaseController
    {

        private $appointments;
        private $todaysappointments;
        private $patientId;
        public function __construct()
        {
            parent::__construct(
                "Medical History",
                ["views/medical_history.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $patientId=intval($_GET['id']);

            try {
                $medHistoryBO = new TreatPatientBO(); 
                $this->appointments = $medHistoryBO->getMedicalHistory($patientId);
                
            } catch (NoDataFoundException $e) {
                parent::setAlertErrorMessage($e->getMessage());
            }

            parent::doGet();

        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {

            $appointments = $this->appointments;
            //$todaysappointments = $this->todaysappointments;
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new MedicalHistoryController();

}
