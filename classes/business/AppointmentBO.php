<?php
/**
 * Medical Specialty Business Object.
 * @author: Leonardo Otoni
 */
namespace classes\business {

    use \classes\dao\AppointmentDao as AppointmentDao;
    use \classes\models\AppointmentModel as AppointmentModel;

    class MedicalSpecialtyBO
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
