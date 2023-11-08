<?php

namespace App\Controllers;


use \Core\View;
use \Core;

/**
 * Home controller
 *
 * PHP version 7.0
 */
class Enchere extends \Core\Controller
{

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        $liste = \App\Models\Enchere::getAll();
        

        foreach($liste as $key => $value){
            $produit = \App\Models\Produit::selectId($value['Produit_id']);
            $offresCount = \App\Models\Offre::selectOffresCount($liste[$key]['id']);
            if (isset($_SESSION['user_id'])) {
                $echereUserFavorite = \App\Models\Encherfavorit::addEncherfavorit($liste[$key]['id'], $_SESSION['user_id']);
            }else{
            
                $echereUserFavorite = 0;
            }
            if($echereUserFavorite){
                $echereUserFavorite = 1;
            }else{

                $echereUserFavorite = 0;
            }
            $liste[$key]['offresCount'] = $offresCount;
            $liste[$key]['nom'] = $produit['nom'];
            $liste[$key]['image_principale'] = $produit['image_principale'];
            $liste[$key]['enchereUserFavorite'] = $echereUserFavorite;

        }

        View::renderTemplate('Enchere/index.html',  [
            'encheres' => $liste,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }

}
