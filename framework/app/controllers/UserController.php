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
        $error = "";
        return $this->view('user/signup.html', ["error" => $error]);
    }
    
    public function signupDoneAction()
    {   
        $admin = new User();
        try{
            $email = $_GET['email'];
            $username = $_GET['username'];
            $password = $_GET['pass'];
            if($admin->findIfAlreadyExists(array($email, $password, $username)) == false){
                if($admin->new(array($email, $password, $username)) == false){
                    return $this->view('pages/home.html');
                }
                else{
                    echo 'error';
                }
            }
            else {
                $error = "Utilizator deja existent";
                return $this->view('user/signup.html', ["error" => $error]);
            }
        } catch(Throwable $e){}
    }

    public function addFunds($userID)
    {   
        $admin = new User();
        $current_user = $admin->get($userID);
        return $this->view('user/add_funds.html', ["user" => $current_user]);
    }

    public function addFundsDone($userID, $sum)
    {   
        $admin = new User();
        try{
            
            if($admin->addFunds($userID, $sum) == false){
                echo 'funds added';
            }
            else {
                echo 'error';
            }
        } catch(Throwable $e){}
    }
}