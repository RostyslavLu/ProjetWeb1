<?php

namespace App\Controllers;

use \Core\View;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Offre extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        View::renderTemplate('Offre/index.html', [
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    public function store()
    {
        $Produit_id = $this->route_params;
        $enchere = \App\Models\Enchere::selectProduitId($Produit_id['id']);
        $offrePlusEleve = \App\Models\Offre::selectOffrePlusEleve($enchere['id']);
        if (!$_SESSION) {
            header('Location: '.$this->url_racine.'user/login');
        } else {
                    $_POST['Membre_id'] = $_SESSION['user_id'];
        $_POST['date'] = date("Y-m-d H:i:s");
        $_POST['Enchere_id'] = $enchere['id'];
       
        $data = $_POST;
        echo "<pre>";
        print_r($data);
        print_r($enchere);
        print_r($offrePlusEleve);
        die();


        $offre = \App\Models\Offre::insert($data);
        header('Location: '.$this->url_racine.'produit/show/'.$Produit_id['id'].'');
        }



    }
}