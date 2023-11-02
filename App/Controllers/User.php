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
            'users' => $liste,
            'url_racine' => $this->url_racine
        ]);
    }

    public function show()
    {

        $id = $this->route_params['id'];
        $showId = \App\Models\User::selectId($id);

        View::renderTemplate('User/show.html', [
            'id' => $id,
            'user' => $showId,
            'url_racine' => $this->url_racine
        ]);
    }

    public function create()
    {
        $liste = \App\Models\User::getAll();
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/create.html', [
            'privileges' => $privilege,
            'url_racine' => $this->url_racine
        ]);
    }
    public function store()
    {
        if (!empty($_POST)) {
            $options = [
                'cost' => 12,
            ];
            $passwordHash = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT, $options);
            $_POST['mot_de_passe'] = $passwordHash;

            $liste = \App\Models\User::insert($_POST);

            header('Location: index');
            exit();
        }

        View::renderTemplate('User/create.html');
    }
    public function update()
    {

        $id = $this->route_params['id'];
        $user = \App\Models\User::selectId($id);
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/update.html', [
            'id' => $id,
            'user' => $user,
            'privileges' => $privilege,
            'url_racine' => $this->url_racine
        ]);
    }
    public function login()
    {

        View::renderTemplate('User/login.html', [
            'url_racine' => $this->url_racine
        ]);
    }
    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"]!="POST") {
            View::renderTemplate('User/login.html', [
                'url_racine' => $this->url_racine
            ]);
            exit();
        }

        if (!empty($_POST)) {

            $userDb = \App\Models\User::checkUser($_POST['courriel']);

            if (password_verify($_POST['mot_de_passe'], $userDb['mot_de_passe'])) {
                echo "Le mot de passe est valide !";
                $_SESSION['user'] = $userDb;
                header('Location: ../index');
                exit();
            } else {
                echo "Le mot de passe est invalide.";
            }
        } 
    }
}
