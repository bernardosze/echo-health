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

    class AppointmentDetailDao
    {

        private const EXCEPTION_ENTRY_NAME_EXISTS = "Operation aborted: Cannot save duplicated Specialty Names into the Database.";
        private const EXCEPTION_ENTRY_NAME_IN_USE = "Operation aborted: Specialty Name cannot be delete because is already in use by one or more Doctors.";

        public function __construct()
        {
        }

        /**
         * Get all Appointments from the Database
         */
        public function getAppointmentDetails($patientId)
        {

            $query = "SELECT p.id,u.FIRST_NAME, u.LAST_NAME, u.BIRTHDAY, a.STATUS FROM user u, patient p, 
            appointment a WHERE a.PATIENT_ID = p.id AND p.USER_PROFILE_USER_ID = u.id and a.PATIENT_ID=:patientId ;";

            try {

                $db = Database::getConnection();
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(":patientId", $patientId);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\AppointmentDetailModel");
                } else {
                    throw new NoDataFoundExceptioN();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }
        

        
        

        

    }

}
