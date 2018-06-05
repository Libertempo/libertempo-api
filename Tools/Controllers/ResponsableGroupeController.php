<?php declare(strict_types = 1);
namespace LibertAPI\Tools\Controllers;

use LibertAPI\Tools\Exceptions\MissingArgumentException;
use LibertAPI\Tools\Interfaces;
use Psr\Http\Message\ServerRequestInterface as IRequest;
use Psr\Http\Message\ResponseInterface as IResponse;
use \Slim\Interfaces\RouterInterface as IRouter;
use LibertAPI\Utilisateur\UtilisateurEntite;
use LibertAPI\Groupe\Responsable;

/**
 * Contrôleur de responsable de groupes
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.7
 *
 * Ne devrait être contacté que par le routeur
 * Ne devrait contacter que le ResponsableRepository
 */
final class ResponsableGroupeController extends \LibertAPI\Tools\Libraries\AController
implements Interfaces\IGetable
{
    public function __construct(Responsable\ResponsableRepository $repository, IRouter $router)
    {
        $this->repository = $repository;
        $this->router = $router;
    }

    /**
     * {@inheritDoc}
     */
    public function get(IRequest $request, IResponse $response, array $arguments) : IResponse
    {
        unset($arguments);
        try {
            $groupes = $this->repository->getList(
                $request->getQueryParams()
            );
        } catch (\UnexpectedValueException $e) {
            return $this->getResponseNoContent($response);
        } catch (\Exception $e) {
            return $this->getResponseError($response, $e);
        }
        $entites = array_map([$this, 'buildData'], $groupes);

        return $this->getResponseSuccess($response, $entites, 200);
    }

    /**
     * Construit le « data » du json
     *
     * @param UtilisateurEntite $entite Responsable
     *
     * @return array
     */
    private function buildData(UtilisateurEntite $entite)
    {
        return [
            'id' => $entite->getId(),
            'login' => $entite->getLogin(),
            'nom' => $entite->getNom(),
            'prenom' => $entite->getPrenom(),
            'isResp' => $entite->isResponsable(),
            'isAdmin' => $entite->isAdmin(),
            'isHr' => $entite->isHautResponsable(),
            'isActif' => $entite->isActif(),
            'password' => $entite->getMotDePasse(),
            'quotite' => $entite->getQuotite(),
            'email' => $entite->getMail(),
            'numeroExercice' => $entite->getNumeroExercice(),
            'planningId' => $entite->getPlanningId(),
            'heureSolde' => $entite->getHeureSolde(),
            'dateInscription' => $entite->getDateInscription(),
            'dateLastAccess' => $entite->getDateLastAccess(),
        ];
    }
}