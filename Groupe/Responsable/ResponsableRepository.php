<?php declare(strict_types = 1);
namespace LibertAPI\Groupe\Responsable;

use LibertAPI\Tools\Libraries\AEntite;

/**
 * {@inheritDoc}
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.5
 * @see \LibertAPI\Tests\Units\Groupe\ResponsableRepository
 */
class ResponsableRepository extends \LibertAPI\Tools\Libraries\ARepository
{
    /*************************************************
     * GET
     *************************************************/

    /**
     * @inheritDoc
     */
    public function getOne(int $id) : AEntite
    {
        throw new \RuntimeException('#' . $id . ' is not a callable resource');
    }

    /**
     * @inheritDoc
     */
    public function getById(int $id) : AEntite
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    public function _getList(array $parametres) : array
    {
        $this->queryBuilder->select('users.*, users.u_login AS id');
        $this->queryBuilder->innerJoin('current', 'conges_users', 'users', 'current.gr_login = u_login');
        $this->setWhere($parametres);
        $res = $this->queryBuilder->execute();

        $data = $res->fetchAll(\PDO::FETCH_ASSOC);
        if (empty($data)) {
            throw new \UnexpectedValueException('No resource match with these parameters');
        }

        $entites = array_map(function ($value) {
            return new UtilisateurEntite($this->getStorage2Entite($value));
        }, $data);

        return $entites;
    }

    /**
     * @inheritDoc
     */
    final protected function getParamsConsumer2Storage(array $paramsConsumer) : array
    {
        unset($paramsConsumer);
        return [];
    }

    /**
     * @inheritDoc
     *
     * Duplication de la fonction dans UtilisateurDao (Cf. decisions.md #2018-02-17)
     */
    final protected function getStorage2Entite(array $dataStorage) : array
    {
        return [
            'id' => $dataStorage['id'],
            'login' => $dataStorage['u_login'],
            'nom' => $dataStorage['u_nom'],
            'prenom' => $dataStorage['u_prenom'],
            'isResp' => $dataStorage['u_is_resp'] === 'Y',
            'isAdmin' => $dataStorage['u_is_admin'] === 'Y',
            'isHr' => $dataStorage['u_is_hr'] === 'Y',
            'isActive' => $dataStorage['u_is_active'] === 'Y',
            'seeAll' => $dataStorage['u_see_all'] === 'Y',
            'password' => $dataStorage['u_passwd'],
            'quotite' => $dataStorage['u_quotite'],
            'email' => $dataStorage['u_email'],
            'numeroExercice' => $dataStorage['u_num_exercice'],
            'planningId' => $dataStorage['planning_id'],
            'heureSolde' => $dataStorage['u_heure_solde'],
            'dateInscription' => $dataStorage['date_inscription'],
            'token' => $dataStorage['token'],
            'dateLastAccess' => $dataStorage['date_last_access'],
        ];
    }

    /*************************************************
     * POST
     *************************************************/

    /**
     * @inheritDoc
     */
    public function postOne(array $data, AEntite $entite)
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    public function _post(AEntite $entite) : int
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /*************************************************
     * PUT
     *************************************************/

    /**
     * @inheritDoc
     */
    public function putOne(array $data, AEntite $entite)
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    public function _put(AEntite $entite)
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    final protected function getEntite2Storage(AEntite $entite) : array
    {
        return [];
    }

    /*************************************************
     * DELETE
     *************************************************/

    /**
     * @inheritDoc
     */
    public function deleteOne(AEntite $entite)
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    public function _delete(int $id) : int
    {
        throw new \RuntimeException('Action is forbidden');
    }

    /**
     * @inheritDoc
     */
    final protected function getTableName() : string
    {
        return 'conges_groupe_resp';
    }
}
