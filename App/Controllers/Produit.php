<?php

namespace App\Controllers;

use \Core\View;
use \Core;

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
        
        $images = \App\Models\Images::selectId($id);
        $enchere = \App\Models\Enchere::selectProduitId($id);
        $produit = \App\Models\Produit::selectId($id);
        $condition = \App\Models\Condition::selectId($produit['Condition_id']);
        $type = \App\Models\Type::selectId($produit['Type_id']);
        $offresCount = \App\Models\Offre::selectOffresCount($enchere['id']);
        
        $produit['condition'] = $condition['type'];
        $produit['enchereId'] = $enchere['id'];
        $produit['date_debut'] = $enchere['date_debut'];
        $produit['date_fin'] = $enchere['date_fin'];
        $produit['prix_plancher'] = $enchere['prix_plancher'];
        $produit['type'] = $type['type_nom'];
        $produit['offresCount'] = $offresCount;
        $produit['membreId'] = $enchere['Membre_id'];

        // echo "<pre>";
        // print_r($produit);
        // die();
        View::renderTemplate('Produit/show.html', [
            'id' => $id,
            'produit' => $produit,
            'images' => $images,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }

    public function create()
    {
        $liste = \App\Models\Produit::getAll();
        $condition = \App\Models\Condition::getAll();
        $types = \App\Models\Type::getAll();
        View::renderTemplate('Produit/create.html', [
            'types' => $types,
            'conditions' => $condition,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    public function store()
    {
        if ($_SERVER["REQUEST_METHOD"]!="POST") {
            View::renderTemplate('Produit/create.html', [
                'url_racine' => $this->url_racine,
                'session' => $_SESSION
            ]);
            exit();
        }

        extract($_POST);

        $validation = new Core\Validation();
        $validation->name('nom')->value($nom)->pattern('words')->min(4)->max(30)->required();
        $validation->name('description')->value($description)->min(4)->max(500);
        $validation->name('prix_plancher')->value($prix_plancher)->pattern('float')->min(1)->max(10)->required();
        $validation->name('date_creation')->value($date_creation)->pattern('int')->min(4)->max(4);
        $validation->name('pays')->value($pays)->pattern('alpha')->min(2)->max(30);
        $validation->name('tirage')->value($tirage)->pattern('int')->min(1)->max(20);
        //$validation->name('dimensions')->value($dimensions)->pattern('text')->max(30);

        if ($validation->isSuccess()){
            $image_principale = $_FILES['image_principale']['name'];
            $tmp_name = $_FILES['image_principale']['tmp_name'];
            $_POST['image_principale'] = $image_principale;

            
            $uploadfile = "assets/img/upload/" . basename($image_principale);
            move_uploaded_file($tmp_name, $uploadfile);
            $data = [
                'nom' => $_POST['nom'],
                'description' => $_POST['description'],
                'date_creation' => $_POST['date_creation'],
                'couleurs' => $_POST['couleurs'],
                'pays' => $_POST['pays'],
                'tirage' => $_POST['tirage'],
                'dimensions' => $_POST['dimensions'],
                'certifie' => $_POST['certifie'],
                'Condition_id' => $_POST['Condition_id'],
                'image_principale' => $image_principale,
                'Type_id' => $_POST['Type_id']
            ];
            $produitId = \App\Models\Produit::insert($data);
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

            // a faire - ajouter données dans la table enchere
            $date_debut = $_POST['date_debut'];
            $date_fin = $_POST['date_fin'];
            $prix_plancher = $_POST['prix_plancher'];
            $membreId = $_SESSION['user_id'];
            $data = [
                'date_debut' => $date_debut,
                'date_fin' => $date_fin,
                'prix_plancher' => $prix_plancher,
                'Membre_id' => $membreId,
                'Produit_id' => $produitId
            ];
            $enchereId = \App\Models\Enchere::insert($data);
            header('Location: ../enchere/index');
            exit();
        } else {
            $errors = $validation->getErrors();
            $condition = \App\Models\Condition::getAll();
            View::renderTemplate('Produit/create.html', [
                'data' => $_POST,
                'errors' => $errors,
                'url_racine' => $this->url_racine,
                'conditions' => $condition,
                'session' => $_SESSION
            ]);
        }
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
