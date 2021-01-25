<?php

namespace App\Controllers;

use App\Models\News;
use Core\Controller;
use Core\Http\Request;

class indexController extends Controller
{
    public function index($id)
    {
        $this->view->render('index.php', compact('id'));
    }

    public function news()
    {

    }
    public function getForm() {

    }
}