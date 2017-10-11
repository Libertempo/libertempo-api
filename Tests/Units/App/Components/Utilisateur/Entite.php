<?php
namespace Tests\Units\App\Components\Utilisateur;

use \App\Components\Utilisateur\Entite as _Entite;

/**
 * Classe de test de l'entité de l'utilisateur
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.2
 */
final class Entite extends \Tests\Units\App\Libraries\AEntite
{
    /**
     * @inheritDoc
     */
    public function testConstructWithId()
    {
        $id = 'Balin';

        $entite = new _Entite(['id' => $id]);

        $this->string($entite->getId())->isIdenticalTo($id);
    }

    /**
     * @inheritDoc
     */
    public function testConstructWithoutId()
    {
        $entite = new _Entite(['token' => 'token']);

        $this->variable($entite->getId())->isNull();
    }

    /**
     * @since 0.3
     */
    public function testGetLogin()
    {
        $entite = new _Entite(['token' => 'token', 'login' => 'login']);

        $this->variable($entite->getLogin())->isNull();
    }

    /**
     * @inheritDoc
     */
    public function testReset()
    {
        $entite = new _Entite(['id' => 'Balin', 'token' => 'token']);

        $this->assertReset($entite);
    }

    /**
     * Teste la méthode populateToken avec un mauvais domaine de définition
     */
    public function testPopulateTokenBadDomain()
    {
        $entite = new _Entite([]);
        $token = '';

        $this->exception(function () use ($entite, $token) {
            $entite->populateToken($token);
        })->isInstanceOf('\DomainException');
    }


    /**
     * Teste la méthode populateToken avec ok
     */
    public function testPopulateTokenOk()
    {
        $entite = new _Entite([]);
        $token = 'AZP3401GJE9#';

        $entite->populateToken($token);

        $this->string($entite->getToken())->isIdenticalTo($token);
    }

    public function testUpdateDateLastAccess()
    {
        $entite = new _Entite(['id' => 3, 'dateLastAccess' => "0"]);

        $this->string($entite->getDateLastAccess())->isIdenticalTo("0");

        $entite->updateDateLastAccess();

        $this->string($entite->getDateLastAccess())->isIdenticalTo(date('Y-m-d H:i'));
    }
}