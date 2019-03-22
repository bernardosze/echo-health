<?php
namespace classes\controllers {

    use \classes\business\AppointmentDetailBO as AppointmentDetailBO;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class AppointmentDetailController extends AppBaseController
    {

        private $appointmentDetail;
        private $patientId;
        public function __construct()
        {
            parent::__construct(
                "Appointment Details",
                ["views/appointment_details.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $patientId = intval($_GET['id']);
            try {
                $apptDetailBO = new AppointmentDetailBO();
                $this->appointmentDetail = $apptDetailBO->getAppointmentDetails($patientId);
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

            $appointmentDetail = $this->appointmentDetail;

            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new AppointmentDetailController();

}
