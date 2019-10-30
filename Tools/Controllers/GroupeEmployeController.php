<?php declare(strict_types = 1);
namespace LibertAPI\Tools\Controllers;

use LibertAPI\Tools\Interfaces;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use \Slim\Interfaces\RouterInterface as IRouter;
use LibertAPI\Groupe\Employe;
use Doctrine\ORM\EntityManager;

/**
 * Contrôleur d'un employé de groupes
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 1.0
 *
 * Ne devrait être contacté que par le routeur
 * Ne devrait contacter que le EmployeRepository
 */
final class GroupeEmployeController extends \LibertAPI\Tools\Libraries\AController
implements Interfaces\IGetable
{
    public function __construct(Employe\EmployeRepository $repository, IRouter $router, EntityManager $entityManager)
    {
        parent::__construct($repository, $router, $entityManager);
    }

    /**
     * {@inheritDoc}
     */
    public function get(IRequest $request, IResponse $response, array $arguments) : IResponse
    {
        $parameters = array_merge($arguments, $request->getQueryParams());
        try {
            $employes = $this->repository->getList($parameters);
        } catch (\UnexpectedValueException $e) {
            return $this->getResponseNoContent($response);
        } catch (\Exception $e) {
            return $this->getResponseError($response, $e);
        }
        $entites = array_map([$this, 'buildData'], $employes);

        return $this->getResponseSuccess($response, $entites, 200);
    }

    /**
     * Construit le « data » du json
     *
     * @param Employe\EmployeEntite $entite employe
     *
     * @return array
     */
    private function buildData(Employe\EmployeEntite $entite) : array
    {
        return [
            'login' => $entite->getLogin(),
        ];
    }
}
