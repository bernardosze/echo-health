<?php
use \classes\database\Database as Database;
$db=Database::getConnection();
$fromTime="select * FROM schedule WHERE doctor_id=1 AND day_of_week='Mon';";
$statement =$db->prepare($fromTime);
$statement->execute();
$TimeMon=$statement->fetchAll();
$statement->closeCursor();
?>


<?php
try {
    $query = "SELECT * FROM schedule limit 6; ";
    $statement = $db->prepare($query);
    $statement->execute();
    $schedule = $statement->fetchAll();
    $statement->closeCursor();
}
catch(PDOException $e)
{
    $errormessage=$e->getMessage();
    echo "<p>An error has occured while connecting to the database: $errormessage</p>";
}
?>



<?php
if(isset($_POST['insert']))
{
    $FromTimeMon=$_POST['FromTimeMon'];
    $FromTimeTue=$_POST['FromTimeTue'];
    $FromTimeWed=$_POST['FromTimeWed'];
    $FromTimeThu=$_POST['FromTimeThu'];
    $FromTimeFri=$_POST['FromTimeFri'];
    $FromTimeSat=$_POST['FromTimeSat'];


    $ToTimeMon=$_POST['ToTimeMon'];
    $ToTimeTue=$_POST['ToTimeTue'];
    $ToTimeWed=$_POST['ToTimeWed'];
    $ToTimeThu=$_POST['ToTimeThu'];
    $ToTimeFri=$_POST['ToTimeFri'];
    $ToTimeSat=$_POST['ToTimeSat'];

//Converting HTML time format to SQL time format
    $time_input_Mon = $FromTimeMon;
    $Mon_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Mon = $Mon_Time->format( 'H:i:s');

    $time_input_Tue = $FromTimeTue;
    $Tue_Time = DateTime::createFromFormat( 'H:i', $time_input_Tue);
    $format_Tue = $Tue_Time->format( 'H:i:s');

    $time_input_Wed = $FromTimeWed;
    $Wed_Time = DateTime::createFromFormat( 'H:i', $time_input_Wed);
    $format_Wed = $Wed_Time->format( 'H:i:s');

    $time_input_Thu = $FromTimeThu;
    $Thu_Time = DateTime::createFromFormat( 'H:i', $time_input_Thu);
    $format_Thu = $Thu_Time->format( 'H:i:s');

    $time_input_Fri = $FromTimeFri;
    $Fri_Time = DateTime::createFromFormat( 'H:i', $time_input_Fri);
    $format_Fri = $Fri_Time->format( 'H:i:s');

    $time_input_Sat = $FromTimeSat;
    $Sat_Time = DateTime::createFromFormat( 'H:i', $time_input_Sat);
    $format_Sat = $Sat_Time->format( 'H:i:s');

//-----------------------------------------------------------------------------
//Formatting the To Time
    $To_time_input_Mon = $ToTimeMon;
    $To_Mon_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Mon = $To_Mon_Time->format( 'H:i:s');

    $To_time_input_Tue = $ToTimeTue;
    $To_Tue_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Tue);
    $To_format_Tue = $To_Tue_Time->format( 'H:i:s');

    $To_time_input_Wed = $ToTimeWed;
    $To_Wed_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Wed);
    $To_format_Wed = $To_Wed_Time->format( 'H:i:s');

    $To_time_input_Thu = $ToTimeThu;
    $To_Thu_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Thu);
    $To_format_Thu = $To_Thu_Time->format( 'H:i:s');

    $To_time_input_Fri = $ToTimeFri;
    $To_Fri_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Fri);
    $To_format_Fri = $To_Fri_Time->format( 'H:i:s');

    $To_time_input_Sat = $ToTimeSat;
    $To_Sat_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Sat);
    $To_format_Sat = $To_Sat_Time->format( 'H:i:s');

    //-----------------------------------------------------------------------------

    $query1="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Mon','$format_Mon','$To_format_Mon',1);";
    $query2="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Tue','$format_Tue','$To_format_Tue',1);";
    $query3="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Wed','$format_Wed','$To_format_Wed',1);";
    $query4="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Thu','$format_Thu','$To_format_Thu',1);";
    $query5="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Fri','$format_Fri','$To_format_Fri',1);";
    $query6="insert ignore into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Sat','$format_Sat','$To_format_Sat',1);";


    $pdostm1=$db->prepare($query1);
    $pdostm1->execute();

    $pdostm2=$db->prepare($query2);
    $pdostm2->execute();

    $pdostm3=$db->prepare($query3);
    $pdostm3->execute();

    $pdostm4=$db->prepare($query4);
    $pdostm4->execute();

    $pdostm5=$db->prepare($query5);
    $pdostm5->execute();

    $pdostm6=$db->prepare($query6);
    $pdostm6->execute();

}

else if(isset($_POST['updateMon']))
{
    $FromTimeMon=$_POST['FromTimeMon'];
    $ToTimeMon=$_POST['ToTimeMon'];

    //Converting HTML time format to SQL time format
    $time_input_Mon = $FromTimeMon;
    $Mon_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Mon = $Mon_Time->format( 'H:i:s');

    //Formatting the To Time
    $To_time_input_Mon = $ToTimeMon;
    $To_Mon_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Mon = $To_Mon_Time->format( 'H:i:s');

    $query="update schedule set `FROM`='$format_Mon', `To` = '$To_format_Mon' WHERE doctor_id=1 AND day_of_week='Mon';";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

else if(isset($_POST['updateTue']))
{
    $FromTimeTue=$_POST['FromTimeTue'];
    $ToTimeTue=$_POST['ToTimeTue'];
//Converting HTML time format to SQL time format
    $time_input_Tue = $FromTimeTue;
    $Tue_Time = DateTime::createFromFormat( 'H:i', $time_input_Tue);
    $format_Tue = $Tue_Time->format( 'H:i:s');
//-----------------------------------------------------------------------------
//Formatting the To Time
    $To_time_input_Tue = $ToTimeTue;
    $To_Tue_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Tue);
    $To_format_Tue = $To_Tue_Time->format( 'H:i:s');

    $query="update schedule set `FROM`='$format_Tue' , `To` = '$To_format_Tue' WHERE doctor_id=1 AND day_of_week='Tue';";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

else if(isset($_POST['updateWed']))
{
    $FromTimeWed=$_POST['FromTimeWed'];
    $ToTimeWed=$_POST['ToTimeWed'];
//Converting HTML time format to SQL time format
    $time_input_Wed = $FromTimeWed;
    $Wed_Time = DateTime::createFromFormat( 'H:i', $time_input_Wed);
    $format_Wed = $Wed_Time->format( 'H:i:s');

//Formatting the To Time
    $To_time_input_Wed = $ToTimeWed;
    $To_Wed_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Wed);
    $To_format_Wed = $To_Wed_Time->format( 'H:i:s');
    $query="update schedule set `FROM`='$format_Wed' ,`To` = '$To_format_Wed' WHERE doctor_id=1 AND day_of_week='Wed';";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

else if(isset($_POST['updateThu']))
{

    $FromTimeThu=$_POST['FromTimeThu'];
    $ToTimeThu=$_POST['ToTimeThu'];
//Converting HTML time format to SQL time format
    $time_input_Thu = $FromTimeThu;
    $Thu_Time = DateTime::createFromFormat( 'H:i', $time_input_Thu);
    $format_Thu = $Thu_Time->format( 'H:i:s');
//-----------------------------------------------------------------------------
//Formatting the To Time
    $To_time_input_Thu = $ToTimeThu;
    $To_Thu_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Thu);
    $To_format_Thu = $To_Thu_Time->format( 'H:i:s');

    $query="update schedule set `FROM`='$format_Thu' , `To` = '$To_format_Thu' WHERE doctor_id=1 AND day_of_week='Thu';";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

else if(isset($_POST['updateFri']))
{
    $FromTimeFri=$_POST['FromTimeFri'];
    $ToTimeFri=$_POST['ToTimeFri'];
//Converting HTML time format to SQL time format
    $time_input_Fri = $FromTimeFri;
    $Fri_Time = DateTime::createFromFormat( 'H:i', $time_input_Fri);
    $format_Fri = $Fri_Time->format( 'H:i:s');
//Formatting the To Time
    $To_time_input_Fri = $ToTimeFri;
    $To_Fri_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Fri);
    $To_format_Fri = $To_Fri_Time->format( 'H:i:s');
    $query="update schedule set `FROM`='$format_Fri' , `To` = '$To_format_Fri' WHERE doctor_id=1 AND day_of_week='Fri';";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

else if(isset($_POST['updateSat']))
{

    $FromTimeSat=$_POST['FromTimeSat'];
    $ToTimeSat=$_POST['ToTimeSat'];
//Converting HTML time format to SQL time format
    $time_input_Sat = $FromTimeSat;
    $Sat_Time = DateTime::createFromFormat( 'H:i', $time_input_Sat);
    $format_Sat = $Sat_Time->format( 'H:i:s');
//-----------------------------------------------------------------------------
//Formatting the To Time
    $To_time_input_Sat = $ToTimeSat;
    $To_Sat_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Sat);
    $To_format_Sat = $To_Sat_Time->format( 'H:i:s');
    $query="update schedule set `FROM`='$format_Sat' , `To` = '$To_format_Sat' WHERE doctor_id=1; AND day_of_week='Sat'";
    $pdostm=$db->prepare($query);
    $pdostm->execute();
}

?>

<div>
    <form method="post">
        <table border="1">
            <h4>Set Your Availability</h4>
            <tr><td>Day of Week</td><td>Monday</td><td>Tuesday</td>
                <td>Wednesday</td><td >Thursday</td><td>Friday</td>
                <td>Saturday</td><!--<td >Sunday</td>--></tr>

            <tr><td>From Time</td>
                <td><input type="time" name="FromTimeMon" min="09:00" value="09:00"></td>
                <td><input type="time" name="FromTimeTue" min="09:00" value="09:00"></td>
                <td><input type="time" name="FromTimeWed" min="09:00" value="09:00"></td>
                <td><input type="time" name="FromTimeThu" min="09:00" value="09:00"></td>
                <td><input type="time" name="FromTimeFri" min="09:00" value="09:00"></td>
                <td><input type="time" name="FromTimeSat" min="09:00" value="09:00"></td>
                <!--<td><input type="time" name="FromTimeSun" min="00:00" value=""></td></tr>-->
            <tr><td>To Time</td>
                <td><input type="time" name="ToTimeMon" max="18:00" value="18:00"></td>
                <td><input type="time" name="ToTimeTue" max="18:00" value="18:00"></td>
                <td><input type="time" name="ToTimeWed" max="18:00" value="18:00"></td>
                <td><input type="time" name="ToTimeThu" max="18:00" value="18:00"></td>
                <td><input type="time" name="ToTimeFri" max="18:00" value="18:00"></td>
                <td><input type="time" name="ToTimeSat" max="18:00" value="18:00"></td>
                <!-- <td><input type="time" name="ToTimeSun" max="18:00" value=""></td>-->
                </td></tr>
            <tr><td></td><td><input type="submit" name="updateMon" value="Update"></td>
                <td><input type="submit" name="updateTue" value="Update"></td>
                <td><input type="submit" name="updateWed" value="Update"></td>
                <td><input type="submit" name="updateThu" value="Update"></td>
                <td><input type="submit" name="updateFri" value="Update"></td>
                <td><input type="submit" name="updateSat" value="Update"></td>
            </tr>

        </table><br>
        <input type="submit" name="insert" value="Submit">
        <input type="submit" name="review" value="Review Your Time Table">

    </form>

    <br>

    <?php if(isset($_POST['review'])){?>
    <h6>Review Working Hours</h6>
    <table border =1>
        <tr><td>Day of Week</td><td>From Time</td><td>To Time</td></tr>

        <?php foreach ($schedule as $sche){ ?>
            <tr><td><?php echo $sche['day_of_week']?></td>
                <td><?php echo $sche['from']?></td>
                <td><?php echo $sche['to']?></td> </tr>
        <?php }}?>

    </table>

</div>
