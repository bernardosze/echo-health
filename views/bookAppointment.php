<?php

use \classes\database\Database as Database;
$db=Database::getConnection();
$doctor_id=$_GET['id'];


    $query = "SELECT * FROM schedule WHERE doctor_id=3; ";
    $statement = $db->prepare($query);
    $statement->execute();
    $schedule = $statement->fetchAll();
    $statement->closeCursor();


?>

<h4>Doctor's Hours</h4>
    <table border =1>
        <tr><td><b>Day of Week<b></b></td><td><b>From Time</b></td><td><b>To Time</b></td></tr>
        <?php foreach ($schedule as $sche){ ?>
            <tr><td><?php echo $sche['day_of_week']?></td>
                <td><?php echo $sche['from']?></td>
                <td><?php echo $sche['to']?></td> </tr>
        <?php }?>
</table>
<p></p>
<h4>Book An Appointment</h4>
<form method="post">
    <table border="1">
        <tr><td>Time</td><td><input type="time" name="time"></td><td>Date</td><td><input type="date" name="date"></td></tr>
    </table>
    <input type="submit" name="book" value="Book Appointment">


</form>
<?php
    if(isset($_POST['book'])){
        $date=$_POST['date'];
        $bookTime=$_POST['time'];
        $time_convert = DateTime::createFromFormat( 'H:i',  $bookTime);
        $time_sql_format = $time_convert->format( 'H:i:s');
        $checkquery="SELECT COUNT(*) FROM appointment where `FROM`= '$date $time_sql_format' and  doctor_id= $doctor_id;";
        $count = $db->prepare($checkquery);
        $count->execute();
        $fetch=$count->fetch();
        $result=$fetch[0];
        $count->closeCursor();


            if($result==0)
            {
                $appointment_query = "INSERT INTO appointment(`FROM`,patient_id, doctor_id) VALUES ('$date $time_sql_format',1, $doctor_id)";
                $statement2 = $db->prepare($appointment_query);
                $statement2->execute();
                $statement2->closeCursor();
                echo "Success";
           }
            else
                {
                    echo "This spot is not available";
               }
    }

?>
