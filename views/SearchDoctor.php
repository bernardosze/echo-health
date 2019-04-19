<?php
use \classes\database\Database as Database;
$db=Database::getConnection();
$doctor="select  first_name, last_name, `name`, doctor.id
FROM `user`
INNER JOIN doctors
ON  user.ID = doctors.USER_PROFILE_USER_ID
INNER JOIN doctor_medical_specialties
ON doctor.id = doctor_medical_specialties.doctor_id
INNER JOIN medical_specialties
ON doctor_medical_specialties.MEDICAL_SPECIALTY_ID=medical_specialties.ID
WHERE doctors.USER_PROFILE_PROFILE_ID=2;
";

$statement =$db->prepare($doctor);
$statement->execute();
$getDoctor=$statement->fetchAll();
$statement->closeCursor();


?>

    <table border =2>
        <tr><td><b>Doctor's ID</b></td><td><b>First Name</b></td>
            <td><b>Last Name</b></td><td><b>Medical Speciality</b></td>
            <td><b>Book Appointment</b></td></tr>

        <?php foreach ($getDoctor as $sche){ ?>
        <tr>
            <td><?php echo $sche['id']?></td>
            <td><?php echo $sche['first_name']?></td>
            <td><?php echo $sche['last_name']?></td>
            <td><?php echo $sche['name']?></td>
            <td><a href="bookAppointment?id=<?php echo $sche['id']?>">Book Appointment</a></td>
            </tr>
        <?php }?>

    </table>
