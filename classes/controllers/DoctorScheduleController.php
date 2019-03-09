<?php
namespace classes\controllers {

    use \classes\business\AppointmentBO as AppointmentBO;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class DoctorScheduleController extends AppBaseController
    {

        private $appointments;

        public function __construct()
        {
            parent::__construct(
                "Schedule",
                ["views/appointment_list.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {

            try {
                $apptBO = new AppointmentBO();
                $this->appointments = $apptBO->getAllAppointments();
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

            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new DoctorScheduleController();

}
