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
        $listeEncheres = \App\Models\Enchere::getAll();
        $listeProduit = \App\Models\Produit::getAll();

        foreach ($listeProduit as $key => $value) {
            //$produit = \App\Models\Produit::selectId($value['Produit_id']);

            $enchere = \App\Models\Enchere::selectId($value['Enchere_id']);
            $offres = \App\Models\Offre::selectOffres($enchere['id']);
            $offresCount = count($offres);


            if ($offresCount > 0) {
                $offresCount = $offresCount . " offre(s)";
            } else {
                $offresCount = "Aucune offre pour cette enchère";
            }

            //montant de l'offre la plus élevée
            $offrePlusEleve = \App\Models\Offre::selectOffrePlusEleve($enchere['id']);

            if ($offrePlusEleve == null) {
                $montantOffrePlusEleve = $enchere['prix_plancher'];
            } else {
                $montantOffrePlusEleve = $offrePlusEleve['montant'];
            }

            if (isset($_SESSION['user_id'])) {

                $echereUserFavorite = \App\Models\Encherfavorit::selectUserEncheresFavorit($value['Enchere_id'], $_SESSION['user_id']);

                if ($echereUserFavorite != null) {
                    $echereUserFavorite = 1;
                } else {
                    $echereUserFavorite = 0;
                }
            } else {

                $echereUserFavorite = null;
            }


            $listeProduit[$key]['offresCount'] = $offresCount;
            $listeProduit[$key]['enchereUserFavorite'] = $echereUserFavorite;
            $listeProduit[$key]['prix_plancher'] = $montantOffrePlusEleve;
            $listeProduit[$key]['Membre_id'] = $enchere['Membre_id'];
            $listeProduit[$key]['Produit_id'] = $value['id'];
            $listeProduit[$key]['date_debut'] = $enchere['date_debut'];
            $listeProduit[$key]['date_fin'] = $enchere['date_fin'];
            $listeProduit[$key]['coup_de_coeur'] = $enchere['coup_de_coer'];
        }
        // echo "<pre>";
        // print_r($listeProduit);
        // die();

        View::renderTemplate('Enchere/index.html',  [
            'encheres' => $listeProduit,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * fonction pour rechercher un timbre par son nom
     */
    public function search()
    {
        $search = $_GET['rechercher'];
        if ($search == "") {
            header('Location: ' . $this->url_racine);
        }
        $liste = \App\Models\Produit::search($search);


        foreach ($liste as $key => $value) {
            $enchere = \App\Models\Enchere::selectId($value['Enchere_id']);
            $liste[$key]['date_debut'] = $enchere['date_debut'];
            $liste[$key]['date_fin'] = $enchere['date_fin'];
            $liste[$key]['prix_plancher'] = $enchere['prix_plancher'];
        }
        $countListe = count($liste);
        if ($countListe > 0) {
            $countListe = $countListe . " résultat(s) pour votre recherche : " . $search;
        } else {
            $countListe = "Aucun résultat pour votre recherche : " . $search . "";
        }

        View::renderTemplate('Enchere/search.html',  [
            'resultats' => $liste,
            'countListe' => $countListe,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * filtre pour rechercher un timbre
     */

    public function searchComplex()
    {
        echo "<pre>";
        print_r($_POST);

        $condition = $_POST['condition'];
        $prixMin = $_POST['prixMin'];
        $prixMax = $_POST['prixMax'];
        $type = $_POST['type'];
        $anneMin = $_POST['anneMin'];
        $anneMax = $_POST['anneMax'];
        $tirage = $_POST['tirage'];
        $certification = $_POST['certification'];

        $liste = \App\Models\Produit::searchComplex($condition, $type, $anneMin, $anneMax, $tirage, $certification);
        foreach ($liste as $key => $value) {
            $enchere = \App\Models\Enchere::selectId($value['Enchere_id']);

            $liste[$key]['prix_plancher'] = $enchere['prix_plancher'];
        }
        print_r($liste);
        die();
        $countListe = count($liste);
        if ($countListe > 0) {
            $countListe = $countListe . " résultat(s) pour votre recherche : " . $condition . " entre " . $prixMin . " et " . $prixMax . "";
        } else {
            $countListe = "Aucun résultat pour votre recherche : " . $condition . " entre " . $prixMin . " et " . $prixMax . "";
        }
    }
    /**
     * fonction pour ajouter une coup de coeur
     */
    public function coupDeCoeur()
    {
        // echo "<pre>";
        
        $enchereId = $this->route_params['id'];
        $enchere = \App\Models\Enchere::selectId($enchereId);
        $userId = \App\Models\Enchere::selectId($enchereId)['Membre_id'];
        $data = [
            'Enchere_id' => $enchereId,
            'Membre_id' => $userId,
            'coups_de_coeur' => $enchere['coup_de_coer']
        ];
        // print_r($data);
        // die();
        $liste = \App\Models\Enchere::insertCoupDeCoeur($data);
        

        header('Location: ../../user/show/' . $_SESSION['user_id']);
    }
    public function deleteCoupDeCoeur()
    {
        $enchereId = $this->route_params['id'];
        $enchere = \App\Models\Enchere::selectId($enchereId);
        $userId = \App\Models\Enchere::selectId($enchereId)['Membre_id'];
        $data = [
            'Enchere_id' => $enchereId,
            'Membre_id' => $userId,
            'coups_de_coeur' => $enchere['coup_de_coer']
        ];
        $liste = \App\Models\Enchere::deleteCoupDeCoeur($data);
        header('Location: ../../user/show/' . $_SESSION['user_id']);
    }
    /**
     * fonction pour afficher les enchères actuelles
     */
    public function encheresActuel()
    {
        $listeEncheres = \App\Models\Enchere::selectEncheresActuel();

        $listeProduit = \App\Models\Produit::getAll();

        foreach ($listeProduit as $key => $value) {
            //$produit = \App\Models\Produit::selectId($value['Produit_id']);

            $enchere = \App\Models\Enchere::selectId($value['Enchere_id']);
            $offres = \App\Models\Offre::selectOffres($enchere['id']);
            $offresCount = count($offres);


            if ($offresCount > 0) {
                $offresCount = $offresCount . " offre(s)";
            } else {
                $offresCount = "Aucune offre pour cette enchère";
            }

            //montant de l'offre la plus élevée
            $offrePlusEleve = \App\Models\Offre::selectOffrePlusEleve($enchere['id']);

            if ($offrePlusEleve == null) {
                $montantOffrePlusEleve = $enchere['prix_plancher'];
            } else {
                $montantOffrePlusEleve = $offrePlusEleve['montant'];
            }

            if (isset($_SESSION['user_id'])) {

                $echereUserFavorite = \App\Models\Encherfavorit::selectUserEncheresFavorit($value['Enchere_id'], $_SESSION['user_id']);

                if ($echereUserFavorite != null) {
                    $echereUserFavorite = 1;
                } else {
                    $echereUserFavorite = 0;
                }
            } else {

                $echereUserFavorite = null;
            }


            $listeProduit[$key]['offresCount'] = $offresCount;
            $listeProduit[$key]['enchereUserFavorite'] = $echereUserFavorite;
            $listeProduit[$key]['prix_plancher'] = $montantOffrePlusEleve;
            $listeProduit[$key]['Membre_id'] = $enchere['Membre_id'];
            $listeProduit[$key]['Produit_id'] = $value['id'];
            $listeProduit[$key]['date_debut'] = $enchere['date_debut'];
            $listeProduit[$key]['date_fin'] = $enchere['date_fin'];
            $listeProduit[$key]['coup_de_coeur'] = $enchere['coup_de_coer'];
        }

        View::renderTemplate('Enchere/index.html',  [
            'encheres' => $listeProduit,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
    /**
     * fonction pour afficher les enchères archivées
     */
    public function encheresArchive()
    {
        $listeEncheres = \App\Models\Enchere::selectEncheresArchive();

        $listeProduit = \App\Models\Produit::getAll();

        foreach ($listeProduit as $key => $value) {
            

            $enchere = \App\Models\Enchere::selectId($value['Enchere_id']);
            $offres = \App\Models\Offre::selectOffres($enchere['id']);
            $offresCount = count($offres);


            if ($offresCount > 0) {
                $offresCount = $offresCount . " offre(s)";
            } else {
                $offresCount = "Aucune offre pour cette enchère";
            }

            //montant de l'offre la plus élevée
            $offrePlusEleve = \App\Models\Offre::selectOffrePlusEleve($enchere['id']);

            if ($offrePlusEleve == null) {
                $montantOffrePlusEleve = $enchere['prix_plancher'];
            } else {
                $montantOffrePlusEleve = $offrePlusEleve['montant'];
            }

            if (isset($_SESSION['user_id'])) {

                $echereUserFavorite = \App\Models\Encherfavorit::selectUserEncheresFavorit($value['Enchere_id'], $_SESSION['user_id']);

                if ($echereUserFavorite != null) {
                    $echereUserFavorite = 1;
                } else {
                    $echereUserFavorite = 0;
                }
            } else {

                $echereUserFavorite = null;
            }


            $listeProduit[$key]['offresCount'] = $offresCount;
            $listeProduit[$key]['enchereUserFavorite'] = $echereUserFavorite;
            $listeProduit[$key]['prix_plancher'] = $montantOffrePlusEleve;
            $listeProduit[$key]['Membre_id'] = $enchere['Membre_id'];
            $listeProduit[$key]['Produit_id'] = $value['id'];
            $listeProduit[$key]['date_debut'] = $enchere['date_debut'];
            $listeProduit[$key]['date_fin'] = $enchere['date_fin'];
            $listeProduit[$key]['coup_de_coeur'] = $enchere['coup_de_coer'];
        }

        View::renderTemplate('Enchere/index.html',  [
            'encheres' => $listeProduit,
            'url_racine' => $this->url_racine,
            'session' => $_SESSION
        ]);
    }
}
