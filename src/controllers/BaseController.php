<?php

namespace App\controllers;

/**
 * Classe de base pour les contrôleurs.
 *
 * Fournit des méthodes utilitaires partagées, telles que le rendu des vues.
 */
class BaseController
{
    /**
     * Rendu d'une vue spécifique avec des variables dynamiques.
     *
     * La méthode extrait les variables fournies en tant que tableau associatif afin qu'elles soient accessibles dans la vue.
     * Elle capture ensuite le contenu de la page spécifiée et l'insère dans un layout global.
     *
     * @param string $page Nom de la page à afficher (chemin relatif dans le dossier `/views/pages` sans extension PHP).
     * @param array $variables Tableau associatif contenant les variables à rendre disponibles dans la vue.
     *
     * @return void
     */
    public static function render(string $page, array $variables = []): void
    {
        extract($variables);
        ob_start();

        include __DIR__ . "/../views/pages/$page.php";

        $content = ob_get_clean();

        include __DIR__ . "/../views/layout.php";
    }

}