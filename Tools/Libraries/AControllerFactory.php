<?php
namespace LibertAPI\Tools\Libraries;

use \Slim\Interfaces\RouterInterface as IRouter;
use LibertAPI\Tools\Libraries\Application;
use LibertAPI\Tools\Libraries\AEntite;

/**
 * Fabrique des contrôleurs, basé sur les dépendances
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.2
 *
 * Ne devrait être contacté que par \LibertAPI\Tools\Middlewares
 * Peut contacter tous les contrôleurs
 */
abstract class AControllerFactory
{
    /**
     * Créé le contrôleur d'authentification
     *
     * @param string $ressourcePath
     * @param \PDO $storageConnector Connecteur à la BDD
     * @param IRouter $router Routeur de l'application
     *
     * @return \LibertAPI\Tools\Libraries\AController
     * @throws \DomainException Si la ressource est inconnue
     */
    final public static function createControllerAuthentification($ressourcePath, \PDO $storageConnector, IRouter $router)
    {
        $controllerClass = static::getControllerClassname($ressourcePath);
        $paths = explode('\\', $ressourcePath);
        $end = array_pop($paths);
        if (!class_exists($controllerClass, true)) {
            throw new \DomainException('Unknown component');
        }

        $daoClass = '\LibertAPI\Utilisateur\UtilisateurDao';
        $repoClass = '\LibertAPI\Utilisateur\UtilisateurRepository';

        $repo = new $repoClass(
            new $daoClass($storageConnector)
        );
        $repo->setApplication(new Application($storageConnector));


        return new $controllerClass($repo, $router);
    }

    /**
     * Créé le contrôleur authentifié
     *
     * @param string $ressourcePath
     * @param \PDO $storageConnector Connecteur à la BDD
     * @param IRouter $router Routeur de l'application
     * @param AEntite $currentUser Utilisateur authentifié
     *
     * @return \App\Libraries\AController
     * @throws \DomainException Si la ressource est inconnue
     */
    final public static function createControllerWithUser($ressourcePath, \PDO $storageConnector, IRouter $router, AEntite $currentUser)
    {
        $controllerClass = static::getControllerClassname($ressourcePath);
        if (!class_exists($controllerClass, true)) {
            throw new \DomainException('Unknown component');
        }

        $daoClass = '\App\Components\\' . $ressourcePath . '\Dao';
        $repoClass = '\App\Components\\' . $ressourcePath . '\Repository';

        return new $controllerClass(
            new $repoClass(
                new $daoClass($storageConnector)
            ),
            $router,
            $currentUser
        );
    }

    /**
     * Résolution du namespace du contrôleur
     *
     * @param string $ressourcePath
     *
     * @return string
     */
    final public static function getControllerClassname($ressourcePath)
    {
        $paths = explode('\\', $ressourcePath);
        $end = array_pop($paths);
        return '\LibertAPI\\' . $ressourcePath . '\\' . $end . 'Controller';
    }
}
