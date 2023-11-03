<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Produit extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $liste = \App\Models\Produit::getAll();

        View::renderTemplate('Produit/index.html',  [
            'produits' => $liste,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }

    public function show()
    {

        $id = $this->route_params['id'];
        $showId = \App\Models\Produit::selectId($id);

        $images = \App\Models\Images::selectId($showId['id']);

        View::renderTemplate('Produit/show.html', [
            'id' => $id,
            'produit' => $showId,
            'images' => $images,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }

    public function create()
    {
        $liste = \App\Models\Produit::getAll();
        $condition = \App\Models\Condition::getAll();
        
        View::renderTemplate('Produit/create.html', [
            'conditions' => $condition,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    public function store()
    {
        if (!empty($_POST)) {
            
            $image_principale = $_FILES['image_principale']['name'];
            $tmp_name = $_FILES['image_principale']['tmp_name'];
            $_POST['image_principale'] = $image_principale;

            
            $uploadfile = "assets/img/upload/" . basename($image_principale);
            move_uploaded_file($tmp_name, $uploadfile);

            $produitId = \App\Models\Produit::insert($_POST);
            $images = $_FILES['image'];
            foreach ($images['name'] as $key => $value) {
                $image = $images['name'][$key];
                $tmp_name = $images['tmp_name'][$key];

                $destination = "assets/img/upload/" . basename($image);
                move_uploaded_file($tmp_name, $destination);
                $data = [
                    'Produit_id' => $produitId,
                    'nom_fichier' => $image
                ];
                $imageId = \App\Models\Images::insert($data);
            }
            
            header('Location: index');
            exit();
        }

        View::renderTemplate('Produit/create.html');
    }
    public function update()
    {

        $id = $this->route_params['id'];
        $produit = \App\Models\Produit::selectId($id);
        $condition = \App\Models\Condition::getAll();
        View::renderTemplate('Produit/update.html', [
            'id' => $id,
            'produit' => $produit,
            'conditions' => $condition,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    public function delete()
    {
        $id = $this->route_params['id'];
        $produit = \App\Models\Produit::delete($id);
        header('Location: ../index');
        exit();
    }
}
