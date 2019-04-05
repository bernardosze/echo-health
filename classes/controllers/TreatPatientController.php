<?php
namespace classes\controllers {
    use \classes\business\AppointmentDetailBO as AppointmentDetailBO;
    use \classes\business\TreatPatientBO as TreatPatientBO;
    use \classes\util\AppConstants as AppConstants;
    use Exception;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class TreatPatientController extends AppBaseController
    {

        public $appointmentDetail;
        private $patientId;
        private $assessment;
        private $prescription;
        private const DATA_SAVED = "Medical Record successfully updated.";
        public function __construct()
        {
            parent::__construct(
                "Treat Patient",
                ["views/treat_patient.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $apptId = intval($_GET['id']);
            try {
                $apptDetailBO = new AppointmentDetailBO();
                $this->appointmentDetail = $apptDetailBO->getAppointmentDetails($apptId);
                
            } catch (NoDataFoundException $e) {
                parent::setAlertErrorMessage($e->getMessage());
            }

            parent::doGet();

        }

        protected function doPost()
        {

            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $apptId = intval($_GET['id']);
            $assessment = $_POST['assessment'];
            $prescriptions = $_POST['prescriptions'];
            $patientId = intval($_POST['patientId']);
            
            
            try {
                
                $treatPatientBO = new TreatPatientBO();
                $treatPatientBO->updateMedicalRecord($apptId,$patientId,$assessment,$prescriptions);

                
                parent::setAlertSuccessMessage("Profile successfully saved.");
            
                
            } catch (Exception $e) {
                parent::setAlertErrorMessage($e->getMessage());
            } finally {
                header ("Location: appointmentdetails?id=$apptId");

            }

        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {

            
            $appointmentDetail = $this->appointmentDetail;
            
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new TreatPatientController();

}
