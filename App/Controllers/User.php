<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class User extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $liste = \App\Models\User::getAll();
        View::renderTemplate('User/index.html',  [
            'users' => $liste
        ]);
    }

    public function show() {

        $id = $this->route_params['id'];
        $showId = \App\Models\User::selectId($id);

        View::renderTemplate('User/show.html', [
            'id' => $id,
            'user' => $showId
        ]);
    }

    public function create() {
        $liste = \App\Models\User::getAll();
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/create.html', [
            'privileges' => $privilege
        ]);
    }
    public function store() {
        //print_r($_POST);
        if(!empty($_POST)) {
            $liste = \App\Models\User::insert($_POST);
            header('Location: index');
            exit();
        }
        View::renderTemplate('User/create.html');
    }
    public function update() {
 
        $id = $this->route_params['id'];
        $user = \App\Models\User::selectId($id);
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/update.html', [
            'id' => $id,
            'user' => $user,
            'privileges' => $privilege
        ]);

    }
}
