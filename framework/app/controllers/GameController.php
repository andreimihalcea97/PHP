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
            $error = "";
            return $this->view('pages/games_store.html', ["store" => $store, "index" => $index, "error" => $error]);
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
                $error = 'Not enough money in your wallet. You can add more anytime!';
                return $this->view('user/add_funds.html', ["user" => $userIndex, "error" => $error]);
            }
            else{
                if ($addGame->buyGame(array($userIndex, $gameIndex)) == false)
                {
                    $boughtGame = $addGame->get($gameIndex);
                    $price = $boughtGame->GamePrice;
                    $admin->subtractFunds($userIndex, $price);
                    $user = (new User)->get($userIndex);
                    $games = (new Game)->getGamesForUser($userIndex);
                    return $this->view('user/show.html', ["user" => $user, "games" => $games]);
                }
                else
                {
                    return $this->view('pages/error.html');
                }
            }
        }
        else
        {
            $store = (new Game)->getAllGames();
            $error = "Game already bought!";
            return $this->view('pages/games_store.html', ["store" => $store, "index" => $userIndex, "error" => $error, "gameOwnedIndex" => $gameIndex]);
        }
    }
}
