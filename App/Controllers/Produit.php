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
            'produits' => $liste
        ]);
    }

    public function show()
    {

        $id = $this->route_params['id'];
        $showId = \App\Models\Produit::selectId($id);

        View::renderTemplate('Produit/show.html', [
            'id' => $id,
            'produit' => $showId
        ]);
    }

    public function create()
    {
        $liste = \App\Models\Produit::getAll();
        $condition = \App\Models\Condition::getAll();

        View::renderTemplate('Produit/create.html', [
            'conditions' => $condition
        ]);
    }
    public function store()
    {
        // print_r($_POST);
        // die();
        if (!empty($_POST)) {
            $liste = \App\Models\Produit::insert($_POST);

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
            'conditions' => $condition
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
