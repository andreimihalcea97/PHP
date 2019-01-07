<?php
namespace App\Controllers;

use Framework\Controller;
use App\Models\User;

/**
 * Class UserController
 */
class UserController extends Controller
{
    public function showAction($id)
    {
        $user = (new User)->get($id);

        return $this->view('user/show.html', ["user" => $user]);
    }

    public function loginAction()
    {   
        $admin = new User();
        try{
            $email = $_GET['email'];
            $password = $_GET['pass'];
            $db = $admin->newDbCon();
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

        //$user = (new User)->get($id);

        //return $this->view('user/show.html', ["user" => $user]);
    }
}
