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

    public function goToStore($id)
    {
        $user = (new User)->get($id);
        return $this->view('user/user_store.html', ["user" => $user]);
    }

    public function loginAction()
    {   
        $admin = new User();
        try{
            $email = $_GET['email'];
            $password = $_GET['pass'];
            $db = $admin->newDbCon();
            $sth = $db->prepare('SELECT ID FROM registeredusers WHERE EMail = :email AND Password = :password');
            $sth->execute(array(':email' => $email, ':password' => $password));
            $result = $sth->fetch();
            if ($result !== false)
            {
                $user = (new User)->get($result->ID);
                return $this->view('user/user_menu.html', ["user" => $user]);
            }
            else
            {
                return $this->view('user/unassigned_user.html');
            }
        } catch(Throwable $e){}
    }

    public function signupAction()
    {   
        return $this->view('user/signup.html');
    }
    
    public function signupDoneAction()
    {   
        $admin = new User();
        try{
            $email = $_GET['email'];
            $username = $_GET['username'];
            $password = $_GET['pass'];
            $result = $admin->new(array($email, $password, $username));
            if ($result !== false)
            {
                $user = (new User)->get($result);
                return $this->view('user/show.html', ["user" => $user]);
            }
            else
            {
                return $this->view('user/unassigned_user.html');
            }
        } catch(Throwable $e){}
    }
}
