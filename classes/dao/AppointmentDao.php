<?php
/**
 * Appointment DAO Class
 * @author: Josh
 */
namespace classes\dao {

    use Exception;
    use PDO;
    use PDOException;
    use \classes\database\Database as Database;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;

    class AppointmentDao
    {

        private const EXCEPTION_ENTRY_NAME_EXISTS = "Operation aborted: Cannot save duplicated Specialty Names into the Database.";
        private const EXCEPTION_ENTRY_NAME_IN_USE = "Operation aborted: Specialty Name cannot be delete because is already in use by one or more Doctors.";

        public function __construct()
        {
        }

        /**
         * Get all Appointments from the Database
         */
        public function getAllAppointments($userId)
        {

            $query = "
            
            SELECT a.id, a.from, a.to, a.patient_id, a.doctor_id,a.status, DATE_FORMAT(a.from,'%d/%m/%Y') AS niceDate, DATE_FORMAT(a.from,'%h:%i %p') AS niceTime, DATE_FORMAT(a.from,'%W') AS dayName  from appointments a,doctors d,users u where a.doctor_id = d.id and d.user_profile_user_id = u.id and u.id=:userId order by niceTime asc;

            ";

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);
                $stmt->bindParam(":userId", $userId);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\AppointmentModel");
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }
        public function getTodaysAppointments()
        {

            $query = "SELECT *, DATE_FORMAT(a.from,'%d/%m/%Y') AS niceDate, DATE_FORMAT(a.from,'%h:%i %p') AS niceTime  from appointments a where DATE_FORMAT(a.from,'%d/%m/%Y') = CURDATE()";

            try {

                $db = Database::getConnection();
                $stmt = $db->prepare($query);
                $stmt->execute();

                if ($stmt->rowCount() > 0) {
                    return $stmt->fetchAll(PDO::FETCH_CLASS, "\classes\models\AppointmentModel");
                } else {
                    throw new NoDataFoundException();
                }

            } finally {
                if (isset($stmt)) {
                    $stmt->closeCursor();
                }
            }

        }

        
        

        

    }

}
