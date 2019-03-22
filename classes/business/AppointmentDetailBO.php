<?php
/**
 * Detailed Appointment Business Object.
 * @author: Joshua Dias
 */
namespace classes\business {

    use \classes\dao\AppointmentDetailDao as AppointmentDetailDao;

    class AppointmentDetailBO
    {
        public function __construct()
        {
        }

        public function getAppointmentDetails()
        {
            $apptDetailDao = new AppointmentDetailDao();
            return $apptDetailDao->getAppointmentDetails();

        }

    }

}
