<?php
namespace LibertAPI\Tests\Units\Journal;

use \LibertAPI\Planning\PlanningController as _Controller;

/**
 * Classe de test du contrôleur de journal
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.5
 */
final class JournalController extends \LibertAPI\Tests\Units\Tools\Libraries\AController
{
    /**
     * @var \LibertAPI\Planning\PlanningRepository Mock du repository associé
     */
    private $repository;

    /**
     * @var \LibertAPI\Planning\PlanningEntite Mock de l'entité associée
     */
    private $entite;

    /**
     * Init des tests
     */
    public function beforeTestMethod($method)
    {
        parent::beforeTestMethod($method);
        $this->mockGenerator->orphanize('__construct');
        $this->mockGenerator->shuntParentClassCalls();
        $this->repository = new \mock\LibertAPI\Journal\JournalRepository();
        $this->mockGenerator->orphanize('__construct');
        $this->entite = new \LibertAPI\Journal\JournalEntite([
            'id' => 42,
            'numeroPeriode' => 88,
            'utilisateurActeur' => '4',
            'utilisateurObjet' => '8',
            'etat' => 'cassé',
            'commentaire' => 'c\'est cassé',
            'date' => 'now',
        ]);
    }

    /*************************************************
     * GET
     *************************************************/

    /**
     * Teste la méthode get d'une liste trouvée
     */
    public function testGetListFound()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = [
            42 => $this->entite,
        ];
        $this->newTestedInstance($this->repository, $this->router);
        $response = $this->testedInstance->get($this->request, $this->response, []);
        $data = $this->getJsonDecoded($response->getBody());

        $this->integer($response->getStatusCode())->isIdenticalTo(200);
        $this->array($data)
            ->integer['code']->isIdenticalTo(200)
            ->string['status']->isIdenticalTo('success')
            ->string['message']->isIdenticalTo('')
            //->array['data']->hasSize(1) // TODO: l'asserter atoum en sucre syntaxique est buggé, faire un ticket
        ;
        $this->array($data['data'][0])->hasKey('id');
    }

    /**
     * Teste la méthode get d'une liste non trouvée
     */
    public function testGetListNotFound()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = function () {
            throw new \UnexpectedValueException('');
        };
        $this->newTestedInstance($this->repository, $this->router);
        $response = $this->testedInstance->get($this->request, $this->response, []);

        $this->assertSuccessEmpty($response);
    }

    /**
     * Teste le fallback de la méthode get d'une liste
     */
    public function testGetListFallback()
    {
        $this->calling($this->request)->getQueryParams = [];
        $this->calling($this->repository)->getList = function () {
            throw new \Exception('');
        };
        $this->newTestedInstance($this->repository, $this->router);

        $this->exception(function () {
            $this->testedInstance->get($this->request, $this->response, []);
        })->isInstanceOf('\Exception');
    }
}