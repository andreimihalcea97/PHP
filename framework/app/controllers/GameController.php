<?php
namespace App\Controllers;

use Framework\Controller;
use App\Models\Game;
use App\Models\User;

/**
 * Class GameStoreController
 */
class GameController extends Controller
{
    public function goToStore($userIndex)
    {
        try{
            $index = $userIndex;
            $store = (new Game)->getAllGames();
            $user = (new User)->get($userIndex);

            $current_user_email = str_replace("@", "%40", $user->EMail);
            $back_to_menu = "?email=" . $current_user_email . "&pass=" . $user->Password;
            $error = "";

            return $this->view('pages/games_store.html', ["store" => $store, "index" => $index, "error" => $error, "back_menu_link" => $back_to_menu]);
        } catch(Throwable $e){
            return $this->view('pages/error.html');
        }
    }

    public function buyGameAction($userIndex, $gameIndex)
    {
        $addGame = new Game();
        $admin = new User();
        $gameValue = $addGame->get($gameIndex)->GamePrice;
        $user = $admin->get($userIndex);
        $store = $addGame->getAllGames();
        $current_user_email = str_replace("@", "%40", $user->EMail);
        $back_to_menu = "?email=" . $current_user_email . "&pass=" . $user->Password;

        if ($addGame->findIfAlreadyBought(array($userIndex, $gameIndex)) == false)
        {
            $availability = $admin->checkWallet($userIndex, $gameValue);
            if($availability == false){
                
                $message = "Not enough money!";
                return $this->view('pages/games_store.html', ["store" => $store, "index" => $userIndex, "message" => $message, "back_menu_link" => $back_to_menu, "gameOwnedIndex" => $gameIndex]);
            }
            else{
                if ($addGame->buyGame(array($userIndex, $gameIndex)) == false)
                {
                    $boughtGame = $addGame->get($gameIndex);
                    $price = $boughtGame->GamePrice;
                    $admin->subtractFunds($userIndex, $price);
                    $message = "Game purchased.";

                    return $this->view('pages/games_store.html', ["store" => $store, "index" => $userIndex, "message" => $message, "back_menu_link" => $back_to_menu, "gamePurchasedIndex" => $gameIndex]);
                }
                else
                {
                    return $this->view('pages/error.html');
                }
            }
        }
        else
        {
            $message = "Game already bought!";
            return $this->view('pages/games_store.html', ["store" => $store, "index" => $userIndex, "message" => $message, "gameOwnedIndex" => $gameIndex, "back_menu_link" => $back_to_menu]);
        }
    }
}
