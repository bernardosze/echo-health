<?php
namespace classes\controllers
{


    use \classes\database\Database as Database;
    $db = Database::getConnection();
    $query= "select * from schedule where doctor_id=3;";
    $pdostm=$db->prepare($query);


    if( !$pdostm->execute())
    {
        require_once "views/templates/header.html";
        require_once "views/set_schedule.php";
        require_once "views/templates/footer.html";
    }
    else

        {
            require_once "views/templates/header.html";
            require_once "views/schedule.php";
            require_once "views/templates/footer.html";

        }








}