<?php
namespace App\Models;

use Framework\GameModel;

class Game extends GameModel
{
    protected $table = 'games';
    protected $userGamesTable = 'usergames';
}