<?php

//namespace App\Controllers;
require_once "../app/config.php";
require_once "../app/models/User.php";

class SignupController
{
    public function signUpAction(){

        //redirect to signup page
        header("Location: http://localhost/framework/public/signup.php");
    }

    public function signUpCompleteAction(){

        //redirect to login page
        header("Location: http://localhost/framework/app/views/login_view.html");
    }
}

?>