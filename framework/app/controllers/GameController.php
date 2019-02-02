<?php
namespace App\Controllers;

use Framework\Controller;
use App\Models\Game;

/**
 * Class GameController
 */
class GameController extends Controller
{
    public function goToStore($userIndex)
    {
        $index = $userIndex;
        $store = (new Game)->getAllGames();
        return $this->view('pages/games_store.html', ["store" => $store, "index" => $index]);
    }

    public function buyGameAction($userIndex, $gameIndex)
    {
        $addGame = new Game();
        if ($addGame->findIfAlreadyBought(array($gameIndex, $userIndex)) == false)
        {
            try{
                if ($addGame->buyGame(array($gameIndex, $userIndex)) == false)
                {
                    echo 'game added to account';
                }
                else
                {
                    echo 'error';
                }
            } catch(Throwable $e){}
        }
        else
        {
           echo 'game already bought';
        }
    }
}
