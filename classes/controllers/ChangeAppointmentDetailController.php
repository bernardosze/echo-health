<?php
namespace classes\controllers {

    use \classes\business\AppointmentDetailBO as AppointmentDetailBO;
    use \classes\util\exceptions\NoDataFoundException as NoDataFoundException;
    use Exception;
    use PDO;
    use PDOException;
    use \classes\models\AppointmentDetailModel as AppointmentDetailModel;
    use \classes\util\AppConstants as AppConstants;
    use \classes\util\base\AppBaseController as AppBaseController;
    use \classes\util\UserSessionProfile as UserSessionProfile;
    use \classes\util\email\EmailSender as EmailSender;
    use \classes\database\Database as Database;


    /**
     * App Home Page Controller
     *
     * @author: Josh
     */
    class ChangeAppointmentDetailController extends AppBaseController
    {
        private $patient;
        private $userName = "";
        private $userEmail = "";
        private $appointmentDetail;
        private $apptId;
        private $oldDateTime;
        private $newStatus="";
        private const DATA_SAVED = "Status successfully updated.";
        public function __construct()
        {
            parent::__construct(
                "Change Appointment Details",
                ["views/changeappointment_details.html"]
            );
        }

        /**
         * Method override.
         * Process GET requests.
         */
        protected function doGet()
        {
            $this->userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $this->oldDateTime = $_GET['from'];
            
            


            parent::doGet();

        }
        protected function doPost()
        {
            $userSessionData = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $apptId = intval($_GET['id']);
            $newStatus = $_POST['newStatus'];
            $newDateTime = $_POST['newDateTime'];
            
            $json = [];
            try {
                
                $appointmentDetailBO = new appointmentDetailBO();
                $appointmentDetailBO->updateAppointmentDetails($apptId,$newStatus,$newDateTime);
                $this->notifyAppointmentChange($newStatus,$newDateTime);
                
                
                $json = ["status" => "ok", "message" => self::DATA_SAVED];
                ob_start();
                header ("Location: appointmentdetails?id=$apptId");

            } catch (Exception $e) {
                $json = ["status" => "error", "message" => $e->getMessage()];
            } finally {
                header('Content-type: application/json');
                echo json_encode($json);
            }

        }
        private function notifyAppointmentChange($newStatus,$newDateTime)
        {
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();
            $apptId = intval($_GET['id']);
            try {
                $db=Database::getConnection();
                $query="select email, first_name from users u, patients p, appointments a where u.id=p.user_profile_user_id and p.id=a.patient_id and a.id=:aId";
                $statement =$db->prepare($query);
                $statement->bindValue(":aId", $apptId);
                $statement->execute();
                while ($row = $statement->fetch(PDO::FETCH_NUM)) {
                    //print "Name: <p>{$row[0] $row[1]}</p>";
                    $message = \file_get_contents('./static/email_template/changeappt.html');
                    $message = str_replace("%username%", "{$row[1]}" , $message);
                    $message = str_replace("%system_time%", date("Y-m-d h:i:s a"), $message);
                    $message = str_replace("%datetime%", $newDateTime , $message);
                    $message = str_replace("%status%", $newStatus , $message);

                    $emailSender = new EmailSender();
                    $emailSender->sendSystemEmail("{$row[0]}", "Appointment Changed", $message);
                  }
                
                $statement->closeCursor();
                
                
                

                //Load Template email
                

            } catch (Exception $e1) {
                echo $e1;
                //TODO: generate a log to register that the email routine is not working
            }

        }

        /**
         * Method override.
         * Render the Controller's view page.
         */
        protected function renderViewPages($views)
        {

            $newStatus = $this->newStatus;
            $oldDateTime = $this->oldDateTime;
            $userSessionProfile = unserialize($_SESSION[AppConstants::USER_SESSION_DATA]);
            $firstName = $userSessionProfile->getFirstName();

            foreach ($views as $view) {
                require_once $view;
            }
        }

    }

    new ChangeAppointmentDetailController();

}
