<?php
/**
 * Appointment Business Object.
 * @author: Joshua Dias
 */
namespace classes\business {

    use \classes\dao\AppointmentDao as AppointmentDao;

    class AppointmentBO
    {
        public function __construct()
        {
        }

        public function getAllAppointments($userId)
        {
            $apptDao = new AppointmentDao();
            return $apptDao->getAllAppointments($userId);

        }

        // public function getTodaysAppointments()
        // {
        //     $todayapptDao = new AppointmentDao();
        //     return $todayapptDao->getTodaysAppointments();

        // }

    }

}
