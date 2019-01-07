<?php

namespace Framework;

use \App\Models\User;

function login()
{
    $admin = new \App\Models\User();
    try{
        $email = $_GET['email'];
        $password = $_GET['pass'];
        $sth = $db->prepare('SELECT EMail, Password FROM registeredusers WHERE EMail = :email AND Password = :password');
        $sth->execute(array(':email' => $email, ':password' => $password));
        $result = $sth->fetch();
        if ($result !== false)
        {
            echo 'Assign';
            die();
            // TODO: -> HERE REDIRECT TO USER_VIEW.HTML
        }
        else
        {
            die();
            echo 'Unassigned';
            // TODO: -> HERE REDIRECT BACK TO LOGIN_VIEW.HTML
        }
    } catch(Throwable $e){}
}

login();