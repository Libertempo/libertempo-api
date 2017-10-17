<?php
namespace LibertAPI\Components\Utilisateur;

use App\Helpers\Formatter;

/**
 * @inheritDoc
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.2
 * @see \LibertAPI\Tests\Units\Components\Utilisateur\Entite
 *
 * Ne devrait être contacté que par le Utilisateur\Repository
 * Ne devrait contacter personne
 */
class Entite extends \LibertAPI\Tools\Libraries\AEntite
{
    public function getToken()
    {
        return $this->getFreshData('token');
    }

    public function getLogin()
    {
    }

    public function getNom()
    {
        return $this->getFreshData('nom');
    }

    public function getDateInscription()
    {
        return $this->getFreshData('dateInscription');
    }

    public function getDateLastAccess()
    {
        return $this->getFreshData('dateLastAccess');
    }

    /**
     * @inheritDoc
     */
    public function populate(array $data)
    {
    }

    /**
     * Insère le token dans l'entité
     *
     * @param string $token Nouveau token d'indentification utilisateur
     *
     * @throws \DomainException Si la donnée n'entre pas dans le domaine de définition, où les erreurs sont jsonEncodée dans le message
     * @example ['nomChamp' => [listeErreurs]]
     */
    public function populateToken($token)
    {
        $this->setToken($token);

        $erreurs = $this->getErreurs();
        if (!empty($erreurs)) {
            throw new \DomainException(json_encode($erreurs));
        }
    }

    /**
     * Tente l'insertion d'une donnée en tant que champ « token »
     *
     * Stocke une erreur si la donnée ne colle pas au domaine
     *
     * @param string $token
     */
    private function setToken($token)
    {
        // domaine de token ?
        if (empty($token)) {
            $this->setErreur('token', 'Le champ est vide');
            return;
        }

        $this->dataUpdated['token'] = $token;
    }

    /**
     * @since 0.3
     */
    public function updateDateLastAccess()
    {
        $this->dataUpdated['dateLastAccess'] = Formatter::timeToSQLDatetime(time());
    }

    /**
     * @inheritDoc
     * @TODO L'entité utilisateur n'a pas de clé primaire en int, donc on surcharge le parent. Mettre une PK en int !
     */
    final protected function setId($id)
    {
        $this->id = (string) $id;
    }
}
