

<div>
    <form method="post" action="schedule">
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


</div>
