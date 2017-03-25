<?php
namespace Tests\Units\App\Components\Authentification;

use \App\Components\Authentification\Controller as _Controller;

/**
 * Classe de test du contrôleur de planning
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.2
 */
final class Controller extends \Tests\Units\App\Libraries\AController
{
    /**
     * @var \mock\App\Components\Authentification\Repository Mock du repository associé
     */
    private $repository;

    /**
     * @var \mock\App\Components\Authentification\Model Mock du modèle associé
     */
    private $model;

    /**
     * Init des tests
     */
    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);
        $this->mockGenerator->orphanize('__construct');
        $this->mockGenerator->shuntParentClassCalls();
        $this->repository = new \mock\App\Components\Authentification\Repository();
        $this->mockGenerator->orphanize('__construct');
        $this->model = new \mock\App\Components\Authentification\Model();
    }

    /*************************************************
     * GET
     *************************************************/

    /**
     * Teste la méthode post d'un json mal formé
     */
    public function testGetBadFormat()
    {
        // Le framework fait du traitement, un mauvais json est simplement null
        $this->request->getMockController()->getParsedBody = null;
        $controller = new _Controller($this->repository, $this->router);

        $response = $controller->get($this->request, $this->response);

        $this->assertError($response, 400);
    }

    /**
     * Teste la méthode get d'une authentification non réussie
     */
    public function testGetNotFound()
    {
        $this->repository->getMockController()->find = function () {
            throw new \UnexpectedValueException('');
        };
        $this->request->getMockController()->getParsedBody = ['data'];
        $controller = new _Controller($this->repository, $this->router);

        $response = $controller->get(
            $this->request,
            $this->response
        );

        $this->assertError($response, 404);
    }

    /**
     * Teste la méthode get d'une authentification réussie
     */
    public function testGetFound()
    {
        $this->repository->getMockController()->find = $this->model;
        $this->repository->getMockController()->regenerateToken = $this->model;
        $this->model->getMockController()->getToken = 'abcde';
        $this->request->getMockController()->getParsedBody = ['data'];
        $controller = new _Controller($this->repository, $this->router);

        $response = $controller->get($this->request, $this->response);
        $data = $this->getJsonDecoded($response->getBody());

        $this->integer($response->getStatusCode())->isIdenticalTo(200);
        $this->array($data)
            ->integer['code']->isIdenticalTo(200)
            ->string['status']->isIdenticalTo('success')
            ->string['message']->isIdenticalTo('')
            ->array['data']->isNotEmpty()
        ;
    }
}
