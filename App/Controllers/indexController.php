<?php


namespace App\Controllers;

use Core\Controller;
use Core\Http\Request;

class indexController extends Controller
{
    public function index()
    {
        $test = 'какой-то параметр';
        $this->render('doc.html.twig', compact('test'));
    }
}