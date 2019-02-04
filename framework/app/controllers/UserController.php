<?php
namespace App\Controllers;

use Framework\Controller;
use App\Models\User;
use App\Models\Game;

/**
 * Class UserController
 */
class UserController extends Controller
{
    public function showAction($id)
    {
        $user = (new User)->get($id);
        $current_user_email = str_replace("@", "%40", $user->EMail);
        $back_to_menu = "?email=" . $current_user_email . "&pass=" . $user->Password;
        $games = (new Game)->getGamesForUser($id);
        return $this->view('user/show.html', ["user" => $user, "games" => $games, "back_menu_link" => $back_to_menu]);
    }

    public function loginAction()
    {   
        $admin = new User();
        try{
            $email = $_GET['email'];
            $password = $_GET['pass'];
            $data = array($email, $password);
            $result = $admin->logIn($data);
            
            if ($result !== false)
            {
                $user = (new User)->get($result->ID);
                return $this->view('user/user_menu.html', ["user" => $user]);
            }
            else
            {
                return $this->view('user/unassigned_user.html');
            }
        } catch(Throwable $e){
            return $this->view('pages/error.html');
        }
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
                    return $this->view('pages/error.html');
                }
            }
            else {
                $error = "User already exists.";
                return $this->view('user/signup.html', ["error" => $error]);
            }
        } catch(Throwable $e){
            return $this->view('pages/error.html');
        }
    }

    public function addFunds($userID)
    {   
        $admin = new User();
        $current_user = $admin->get($userID);
        $current_user_email = str_replace("@", "%40", $current_user->EMail);
        $back_to_menu = "?email=" . $current_user_email . "&pass=" . $current_user->Password;
        return $this->view('user/add_funds.html', ["user" => $current_user, "back_menu_link" => $back_to_menu]);
    }

    public function addFundsDone($userID, $sum)
    {   
        $admin = new User();
        $current_user = $admin->get($userID);
        $current_user_email = str_replace("@", "%40", $current_user->EMail);
        $back_to_menu = "?email=" . $current_user_email . "&pass=" . $current_user->Password;
        if($admin->addFunds($userID, $sum) == false){
            $confirmation = "Funds added successfully!";
            return $this->view('user/add_funds.html', ["confirmation" => $confirmation, "user" => $userID, "back_menu_link" => $back_to_menu]);
        }
        else {
            return $this->view('pages/error.html');
        }
    }
}