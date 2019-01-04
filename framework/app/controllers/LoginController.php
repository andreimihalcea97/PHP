<?php

//namespace App\Controllers;

class LoginController
{
    public function logInAction(){

        // echo ('<form action="/framework/public/login.php" method="post">');
        // echo ('</form>');
        //testare user si parola
        header("Location: http://localhost/framework/public/login.php");

    }

    public function logOutAction(){

        echo "log out";

    }

}

?>