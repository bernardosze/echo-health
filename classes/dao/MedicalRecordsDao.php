<?php
/**
 * Appointment Detail DAO Class
 * @author: Josh
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use \classes\util\exceptions\UpdateUserDataException as UpdateUserDataException;

    class MedicalRecordsDao
    {

        private const EXCEPTION_ENTRY_NAME_EXISTS = "Operation aborted: Cannot save duplicated Specialty Names into the Database.";
        private const EXCEPTION_ENTRY_NAME_IN_USE = "Operation aborted: Specialty Name cannot be delete because is already in use by one or more Doctors.";

        public function __construct()
        {
        }

        /**
         * Get all Appointments from the Database
         */

         //TODO gets past medical records from database
        public function getMedicalHistory($patientId)
        {

            $query = "SELECT * FROM medical_records where patient_id =:patientId;";

            try {

                $db = Database::getConnection();
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(":patientId", $patientId);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\MedicalHistoryModel");
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        public function updateMedicalRecord($apptId,$patientId, $assessment, $prescriptions)
        {

            $updateQuery = "replace into medical_records (appointment_id, patient_id, date, assessment, prescription)" . 
                            "values (:apptId, :patientId, curdate(), :assessment, :prescription)";
            

            try {
                $db = Database::getConnection();
                $stmt = $db->prepare($updateQuery);

                
                $stmt->bindValue(":apptId", $apptId);
                $stmt->bindValue(":patientId", $patientId);
                $stmt->bindValue(":assessment", $assessment);
                $stmt->bindValue(":prescription", $prescriptions);
                $stmt->execute();

            } catch (Exception $e) {
                throw new UpdateUserDataException(self::UPDATE_USER_PASSWD_ERROR . $e->getMessage());
            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }
        }
        

        
        

        

    }

}
