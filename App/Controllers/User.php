<?php

namespace App\Controllers;

use \Core\View;
use \Core;

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
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * fonction pour afficher le détail d'un utilisateur
     */
    public function show()
    {
        $id = $this->route_params['id'];

        $user = \App\Models\User::selectId($id);
        $tousEncheres = \App\Models\Enchere::getAll();
        foreach ($tousEncheres as $key => $value) {
            $produit = \App\Models\Produit::selectEnchereId($value['id']);
            print_r($value['coup_de_coer']);
            if ($value['coup_de_coer'] == 1) {
                $tousEncheres[$key]['coup_de_coer'] = "Oui";
            } else {
                $tousEncheres[$key]['coup_de_coer'] = "Non";
            }
            foreach ($produit as $key2 => $value2) {
                $tousEncheres[$key]['nom'] = $value2['nom'];
                $tousEncheres[$key]['Produit_id'] = $value2['id'];
            }
        }

        $encheres = \App\Models\Enchere::selectMembreId($id);

        $enchereFavorite = \App\Models\Encherfavorit::selectEncherfavorit($id);
        if (is_array($enchereFavorite) || is_object($enchereFavorite)) {
            foreach ($enchereFavorite as $key => $value) {
                $produit = \App\Models\Produit::selectEnchereId($value['Enchere_id']);
                foreach ($produit as $key2 => $value2) {
                    $enchere = \App\Models\Enchere::selectId($value2['Enchere_id']);
                    $enchereFavorite[$key]['nom'] = $value2['nom'];
                    $enchereFavorite[$key]['Produit_id'] = $value2['id'];
                    $enchereFavorite[$key]['date_debut'] = $enchere['date_debut'];
                    $enchereFavorite[$key]['date_fin'] = $enchere['date_fin'];
                }
            }
        }
        foreach ($encheres as $key => $value) {
            $produit = \App\Models\Produit::selectEnchereId($value['id']);

            foreach ($produit as $key2 => $value2) {
                $encheres[$key]['nom'] = $value2['nom'];
                $encheres[$key]['Produit_id'] = $value2['id'];
            }
        }

        View::renderTemplate('User/show.html', [
            'id' => $id,
            'user' => $user,
            'encheres' => $encheres,
            'tousEncheres' => $tousEncheres,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION,
            'enchereFavorite' => $enchereFavorite
        ]);
    }
    /**
     *  le formulaire de création d'un utilisateur
     */
    public function create()
    {
        $liste = \App\Models\User::getAll();
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/create.html', [
            'privileges' => $privilege,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }

    /**
     * fonction pour enregistrer un utilisateur dans la base de données
     */

    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            View::renderTemplate('User/create.html', [
                'url_racine' => $this->url_racine,
                'session' => $_SESSION
            ]);
            exit();
        }

        extract($_POST);
        $validation = new Core\Validation();
        $validation->name('nom')->value($nom)->pattern('alpha')->min(2)->max(25)->required();
        $validation->name('prenom')->value($prenom)->pattern('alpha')->min(2)->max(25)->required();
        $validation->name('courriel')->value($courriel)->pattern('email')->min(10)->max(50)->required();
        $validation->name('mot_de_passe')->value($mot_de_passe)->pattern('alphanum')->min(8)->max(25)->required();


        if ($validation->isSuccess()) {
            $options = [
                'cost' => 12,
            ];
            $passwordHash = password_hash($_POST['mot_de_passe'], PASSWORD_BCRYPT, $options);
            $_POST['mot_de_passe'] = $passwordHash;
            $_POST['Privilege_id'] = 2;
            $liste = \App\Models\User::insert($_POST);

            header('Location: ../index');
            exit();
        } else {
            $errors = $validation->getErrors();
            View::renderTemplate('User/create.html', [
                'data' => $_POST,
                'errors' => $errors,
                'url_racine' => $this->url_racine,
                'session' => $_SESSION
            ]);
        }
    }
    /**
     * fonction pour mise à jour d'un utilisateur
     */
    public function update()
    {

        $id = $this->route_params['id'];
        $user = \App\Models\User::selectId($id);
        $privilege = \App\Models\Privilege::getAll();
        View::renderTemplate('User/update.html', [
            'id' => $id,
            'user' => $user,
            'privileges' => $privilege,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * formulaire pour se connecter
     */

    public function login()
    {

        View::renderTemplate('User/login.html', [
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * fonction verifier si l'utilisateur existe dans la base de données et si le mot de passe est valide
     */
    public function auth()
    {
        if ($_SERVER["REQUEST_METHOD"] != "POST") {
            View::renderTemplate('User/login.html', [
                'url_racine' => $this->url_racine,
                'session' => $_SESSION
            ]);
            exit();
        }

        if (!empty($_POST)) {

            $userDb = \App\Models\User::checkUser($_POST['courriel']);
            // ajouter la verification de l'adresse courriel et retourner un message d'erreur si l'adresse n'existe pas

            if (password_verify($_POST['mot_de_passe'], $userDb['mot_de_passe'])) {
                session_regenerate_id();
                $_SESSION['user_id'] = $userDb['id'];
                $_SESSION['user_nom'] = $userDb['nom'];
                $_SESSION['user_prenom'] = $userDb['prenom'];
                $_SESSION['privilege'] = $userDb['Privilege_id'];
                $_SESSION['fingerPrint'] = md5($_SERVER['HTTP_USER_AGENT'] . $_SERVER['REMOTE_ADDR']);

                header('Location: show/' . $userDb['id']);
                exit();
            } else {
                $error = "Le mot de passe est invalide.";
                $data = $_POST;
                View::renderTemplate('User/login.html', [
                    'data' => $data,
                    'error' => $error,
                    'url_racine' => $this->url_racine,
                    'session' => $_SESSION
                ]);
            }
        }
    }
    /**
     * ajouter une enchère à la liste des enchères favorites
     */
    public function offreFavorite()
    {
        $enchereId = $this->route_params['id'];
        $userId = $_SESSION['user_id'];
        $data = [
            'Enchere_id' => $enchereId,
            'Membre_id' => $userId,
        ];

        $liste = \App\Models\Encherfavorit::insert($data);

        header('Location: ../../enchere/index');
    }
    /**
     * supprimer une enchère de la liste des enchères favorites
     */
    public function offreFavoriteDelete()
    {
        $enchereId = $this->route_params['id'];
        $userId = $_SESSION['user_id'];
        $liste = \App\Models\Encherfavorit::delete($enchereId, $userId);

        header('Location: ../../enchere/index');
    }

    /**
     * fonction pour fermer la session d'un utilisateur
     */

    public function logout()
    {
        session_destroy();
        header('Location: ../index');
        exit();
    }

}
