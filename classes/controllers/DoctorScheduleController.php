<?php
namespace classes\controllers {

    use Exception;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\business\AppointmentBO as AppointmentBO;
    use \classes\models\AppointmentModel as AppointmentModel;

    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class DoctorScheduleController extends AppBaseController
    {

        public function __construct()
        {
            parent::__construct(
                "Schedule",
                ["views/appointment_list.html"]
            );
        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {
            $apptBO = new AppointmentBO();
            $from= $apptBO->getFrom();
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new DoctorScheduleController();

}
