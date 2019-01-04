<?php
require_once "../app/config.php";
require_once "../app/models/User.php";

try{
    $admin = new User();
    $db = $admin->newDbCon();
    try{
        $email = $_GET['email'];
        $password = $_GET['pass'];
        $sth = $db->prepare('SELECT EMail, Password FROM registeredusers WHERE EMail = :email AND Password = :password');
        $sth->execute(array(':email' => $email, ':password' => $password));
        $result = $sth->fetch();
        if ($result !== false)
        {
            echo 'Assigned';
            // TODO: -> HERE REDIRECT TO USER_VIEW.HTML
        }
        else
        {
            echo 'Unassigned';
            // TODO: -> HERE REDIRECT BACK TO LOGIN_VIEW.HTML
        }
    } catch(Throwable $e){}
    echo('<div id="header" style="width:500px;text-align: left;">');
    echo('Connected to database ');
    echo('<div class="circle_green" id="centered" style="text-align: center; width: 10; height: 10"></div>');
    echo('</div>');
} catch (Throwable $e) {
    echo('<div id="header" style="width:500px;text-align: left;">');
    echo('Connected to database ');
    echo('<div class="circle_red" id="centered" style="text-align: center; width: 10; height: 10"></div>');
    echo('</div>');
}
?>