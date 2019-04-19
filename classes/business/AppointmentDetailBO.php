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

        public function getAppointmentDetails($apptId)
        {
            $apptDetailDao = new AppointmentDetailDao();
            return $apptDetailDao->getAppointmentDetails($apptId);

        }

        public function updateAppointmentDetails($apptId, $newStatus, $newDateTime)
        {

            $appointmentDetailDao = new AppointmentDetailDao();
            return $appointmentDetailDao->updateAppointmentDetails($apptId, $newStatus, $newDateTime);

        }

    }

}
