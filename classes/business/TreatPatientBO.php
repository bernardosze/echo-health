<?php
/**
 * Detailed Appointment Business Object.
 * @author: Joshua Dias
 */
namespace classes\business {

    use \classes\dao\MedicalRecordsDao as MedicalRecordsDao;

    class TreatPatientBO
    {
        public function __construct()
        {
        }

        public function getMedicalHistory($patientId)
        {

            $medicalRecordsDao = new MedicalRecordsDao();
            return $medicalRecordsDao->getMedicalHistory($patientId);

        }

        public function updateMedicalRecord($apptId, $patientId, $assessment, $prescriptions)
        {

            $medicalRecordsDao = new MedicalRecordsDao();
            return $medicalRecordsDao->updateMedicalRecord($apptId, $patientId, $assessment, $prescriptions);

        }

    }

}
