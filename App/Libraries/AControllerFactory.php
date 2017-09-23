<?php
namespace LibertAPI\App\Libraries;

use \Slim\Interfaces\RouterInterface as IRouter;

/**
 * Fabrique des contrôleurs, basé sur les dépendances
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.2
 *
 * Ne devrait être contacté que par \LibertAPI\Middlewares
 * Peut contacter tous les contrôleurs
 */
abstract class AControllerFactory
{
    /**
     * Créé le bon contrôleur avec les bonnes dépendances en fonction de la requête
     *
     * @param string $ressourcePath
     * @param \PDO $storageConnector Connecteur à la BDD
     * @param IRouter $router Routeur de l'application
     *
     * @return \LibertAPI\App\Libraries\AController
     * @throws \DomainException Si la ressource est inconnue
     */
    final static function createController($ressourcePath, \PDO $storageConnector, IRouter $router)
    {
        $controllerClass = static::getControllerClassname($ressourcePath);
        if (!class_exists($controllerClass, true)) {
            throw new \DomainException('Unknown component');
        }

        switch ($ressourcePath) {
            case 'Authentification':
                $daoClass = '\LibertAPI\App\Components\Utilisateur\Dao';
                $repoClass = '\LibertAPI\App\Components\Utilisateur\Repository';

                $repo = new $repoClass(
                    new $daoClass($storageConnector)
                );
                $repo->setApplication(new Application($storageConnector));

                return new $controllerClass($repo, $router);

            default:
                $daoClass = '\LibertAPI\App\Components\\' . $ressourcePath . '\Dao';
                $repoClass = '\LibertAPI\App\Components\\' . $ressourcePath . '\Repository';

                return new $controllerClass(
                    new $repoClass(
                        new $daoClass($storageConnector)
                    ),
                    $router
                );
        }
    }

    /**
     * Résolution du namespace du contrôleur
     *
     * @param string $ressourcePath
     *
     * @return string
     */
    final static function getControllerClassname($ressourcePath)
    {
        return '\LibertAPI\App\Components\\' . $ressourcePath . '\Controller';
    }
}
