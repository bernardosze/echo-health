<?php
use \classes\database\Database as Database;
?>


<body>
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

    </table>
    <input type="submit" name="submit">
</form>
<?php if(isset($_POST['submit'])){
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


    $db=Database::getConnection();
    //Converting HTML time format to SQL time format
    $time_input_Mon = $FromTimeMon;
    $Mon_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Mon = $Mon_Time->format( 'H:i:s');

    $time_input_Tue = $FromTimeTue;
    $Tue_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Tue = $Tue_Time->format( 'H:i:s');

    $time_input_Wed = $FromTimeWed;
    $Wed_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Wed = $Wed_Time->format( 'H:i:s');

    $time_input_Thu = $FromTimeThu;
    $Thu_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Thu = $Thu_Time->format( 'H:i:s');

    $time_input_Fri = $FromTimeFri;
    $Fri_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Fri = $Fri_Time->format( 'H:i:s');

    $time_input_Sat = $FromTimeSat;
    $Sat_Time = DateTime::createFromFormat( 'H:i', $time_input_Mon);
    $format_Sat = $Sat_Time->format( 'H:i:s');

    //-----------------------------------------------------------------------------
    //Formatting the To Time
    $To_time_input_Mon = $ToTimeMon;
    $To_Mon_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Mon = $To_Mon_Time->format( 'H:i:s');

    $To_time_input_Tue = $ToTimeTue;
    $To_Tue_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Tue = $To_Tue_Time->format( 'H:i:s');

    $To_time_input_Wed = $ToTimeWed;
    $To_Wed_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Wed = $To_Wed_Time->format( 'H:i:s');

    $To_time_input_Thu = $ToTimeThu;
    $To_Thu_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Thu = $To_Thu_Time->format( 'H:i:s');

    $To_time_input_Fri = $ToTimeFri;
    $To_Fri_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Fri = $To_Fri_Time->format( 'H:i:s');

    $To_time_input_Sat = $ToTimeSat;
    $To_Sat_Time = DateTime::createFromFormat( 'H:i', $To_time_input_Mon);
    $To_format_Sat = $To_Sat_Time->format( 'H:i:s');




    //-----------------------------------------------------------------------------

    $query1="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Mon','$format_Mon','$To_format_Mon',1);";
    $query2="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Tue','$format_Tue','$To_format_Tue',1);";
    $query3="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Wed','$format_Wed','$To_format_Wed',1);";
    $query4="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Thu','$format_Thu','$To_format_Thu',1);";
    $query5="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Fri','$format_Fri','$To_format_Fri',1);";
    $query6="insert into schedule (day_of_week,`FROM`,`TO`,doctor_id) values('Sat','$format_Sat','$To_format_Sat',1);";


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

}?>
