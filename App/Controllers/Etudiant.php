<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Etudiant extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $liste = \App\Models\Etudiant::getAll();
        View::renderTemplate('Etudiant/index.html',  [
            'etudiants' => $liste
        ]);
    }

    public function show() {
        print_r($this->route_params);
        $id = $this->route_params['id'];
        View::renderTemplate('Etudiant/show.html', [
            'id' => $id
        ]);
    }

    public function create() {
        $liste = \App\Models\Etudiant::getAll();
        View::renderTemplate('Etudiant/create.html');
    }
    
    public function store() {
        //print_r($_POST);
        if(!empty($_POST)) {
            $liste = \App\Models\Etudiant::insert($_POST);
            header('Location: ProjetWeb1/public/etudiant/index');
            exit();
        }
        View::renderTemplate('Etudiant/create.html');
    }
    public function update() {
        print_r($this->route_params);
        $id = $this->route_params['id'];

    }
}
