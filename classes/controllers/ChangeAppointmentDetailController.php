<?php
namespace classes\controllers {

    use \classes\business\AppointmentDetailBO as AppointmentDetailBO;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use Exception;
    use \classes\models\AppointmentDetailModel as AppointmentDetailModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\UserSessionProfile as UserSessionProfile;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class ChangeAppointmentDetailController extends AppBaseController
    {

        private $appointmentDetail;
        private $apptId;
        private $oldDateTime;
        private $newStatus="";
        private const DATA_SAVED = "Status successfully updated.";
        public function __construct()
        {
            parent::__construct(
                "Change Appointment Details",
                ["views/changeappointment_details.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $this->userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $this->oldDateTime = $_GET['from'];


            parent::doGet();

        }
        protected function doPost()
        {
            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $apptId = intval($_GET['id']);
            $newStatus = $_POST['newStatus'];
            $newDateTime = $_POST['newDateTime'];
            
            $json = [];
            try {
                
                $appointmentDetailBO = new appointmentDetailBO();
                $appointmentDetailBO->updateAppointmentDetails($apptId,$newStatus,$newDateTime);

                
                $json = ["status" => "ok", "message" => self::DATA_SAVED];
                ob_start();
                header ("Location: appointmentdetails?id=$apptId ");

            } catch (Exception $e) {
                $json = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
            }

        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {

            $newStatus = $this->newStatus;
            $oldDateTime = $this->oldDateTime;
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new ChangeAppointmentDetailController();

}
