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
        foreach($liste as $key => $value){
            $enchere = \App\Models\Enchere::selectProduitId($liste[$key]['id']);
            $liste[$key]['date_debut'] = $enchere['date_debut'];
            $liste[$key]['date_fin'] = $enchere['date_fin'];
            $liste[$key]['prix_plancher'] = $enchere['prix_plancher'];
        }
        $countListe = count($liste);
        if ($countListe > 0) {
            $countListe = $countListe." résultat(s) pour votre recherche : ".$search;
        } else {
            $countListe = "Aucun résultat pour votre recherche : ".$search."";
        } 
        // echo "<pre>";
        // print_r($liste);
        // die();

        View::renderTemplate('Recherche/index.html',  [
            'resultats' => $liste,
            'countListe' => $countListe,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
}