<?php
//namespace App\Controllers;
//use Framework\Model;
require_once "Model.php";

class User extends Model 
{
    //we have to set specify the corresponding model for the table    
 	protected $table = "registeredUsers";
}