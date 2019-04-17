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
        public function getAppointmentDetails($apptId)
        {

            $query = "SELECT a.id AS aid, p.id, u.FIRST_NAME, u.LAST_NAME, u.BIRTHDAY, a.STATUS, a.from FROM users u, patients p, 
            appointments a WHERE a.PATIENT_ID = p.id AND p.USER_PROFILE_USER_ID = u.id and a.id=:aId ;";

            try {

                $db = Database::getConnection();
                
                $stmt = $db->prepare($query);
                $stmt->bindValue(":aId", $apptId);
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

        public function updateAppointmentDetails($apptId, $newStatus, $newDateTime)
        {

            $updateQuery = "update appointments a set a.status = :status, a.from =:newDateTime, a.to= DATE_ADD(:newDateTime, INTERVAL 30 MINUTE) where id = :apptId";

            try {
                $db = Database::getConnection();
                $stmt = $db->prepare($updateQuery);

                $stmt->bindValue(":status", $newStatus);
                $stmt->bindValue(":apptId", $apptId);
                $stmt->bindValue(":newDateTime", $newDateTime);
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
