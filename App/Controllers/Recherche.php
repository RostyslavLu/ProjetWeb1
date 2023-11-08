<?php

namespace App\Controllers;

use \Core\View;
use \Core;

/**
 * Recherche controller
 *
 * PHP version 7.0
 */
class Recherche extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $search = $_GET['rechercher'];
        $liste = \App\Models\Produit::search($search);
        echo "<pre>";
        print_r($liste);
        print_r($search);
        die();

        View::renderTemplate('Recherche/index.html',  [
            'resultats' => $liste,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
}