<?php
/**
 * Medical Specialty Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\AppointmentDao as AppointmentDao;

    class AppointmentBO
    {
        public function __construct()
        {
        }

        public function getAllAppointments()
        {
            $apptDao = new AppointmentDao();
            return $apptDao->getAllAppointments();

        }

    }

}
