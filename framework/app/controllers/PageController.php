<?php
namespace App\Controllers;

use Framework\Controller;

/**
 * Class PageController
 */
class PageController extends Controller
{
    public function aboutUsAction()
    {
        return $this->view("pages/about-us.html");
    }

    public function homeAction()
    {
        return $this->view("pages/home.html");
    }
}
