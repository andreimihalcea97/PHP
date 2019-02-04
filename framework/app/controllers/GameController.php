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
            return $this->view('pages/games_store.html', ["store" => $store, "index" => $index]);
        } catch(Throwable $e){
            return $this->view('pages/error.html');
        }
    }

    public function buyGameAction($userIndex, $gameIndex)
    {
        $addGame = new Game();
        $gameValue = $addGame->get($gameIndex)->GamePrice;
        $admin = new User();
        if ($addGame->findIfAlreadyBought(array($userIndex, $gameIndex)) == false)
        {
            $availability = $admin->checkWallet($userIndex, $gameValue);
            if($availability == false){
                echo 'not enough money to account_view';
                //redirect the user to add funds? or store?
            }
            else{
                if ($addGame->buyGame(array($userIndex, $gameIndex)) == false)
                {
                    echo 'game added to account_view';
                }
                else
                {
                    return $this->view('pages/error.html');
                }
            }
        }
        else
        {
           echo 'game already bought_view';
        }
    }
}
