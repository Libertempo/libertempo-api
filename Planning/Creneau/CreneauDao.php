<?php declare(strict_types = 1);
namespace LibertAPI\Planning\Creneau;

use LibertAPI\Tools\Libraries\AEntite;

/**
 * {@inheritDoc}
 *
 * @author Prytoegrian <prytoegrian@protonmail.com>
 * @author Wouldsmina
 *
 * @since 0.1
 *
 * Ne devrait être contacté que par Planning\Creneau\Repository
 * Ne devrait contacter personne
 */
class CreneauDao extends \LibertAPI\Tools\Libraries\ADao
{
    /*************************************************
     * GET
     *************************************************/


    /**
     * Définit les filtres à appliquer à la requête
     *
     * @param array $parametres
     */
    private function setWhere(array $parametres)
    {
        if (!empty($parametres['id'])) {
            $this->queryBuilder->andWhere('creneau_id = :id');
            $this->queryBuilder->setParameter(':id', (int) $parametres['id']);
        }
        if (!empty($parametres['planning_id'])) {
            $this->queryBuilder->andWhere('planning_id = :planningId');
            $this->queryBuilder->setParameter(':planningId', (int) $parametres['planning_id']);
        }
    }

    /*************************************************
     * POST
     *************************************************/



    /**
     * Définit les values à insérer
     *
     * @param array $values
     */
    private function setValues(array $values)
    {
        $this->queryBuilder->setValue('planning_id', (int) $values['planning_id']);
        $this->queryBuilder->setValue('jour_id', (int) $values['jour_id']);
        $this->queryBuilder->setValue('type_semaine', $values['type_semaine']);
        $this->queryBuilder->setValue('type_periode', $values['type_periode']);
        $this->queryBuilder->setValue('debut', $values['debut']);
        $this->queryBuilder->setValue('fin', $values['fin']);
    }


    private function setSet(array $parametres)
    {
        if (!empty($parametres['planning_id'])) {
            $this->queryBuilder->set('planning_id', ':planning_id');
            $this->queryBuilder->setParameter(':planning_id', $parametres['planning_id']);
        }
        if (!empty($parametres['jour_id'])) {
            $this->queryBuilder->set('jour_id', ':jour_id');
            $this->queryBuilder->setParameter(':jour_id', (int) $parametres['jour_id']);
        }
        if (!empty($parametres['type_semaine'])) {
            $this->queryBuilder->set('type_semaine', ':type_semaine');
            $this->queryBuilder->setParameter(':type_semaine', $parametres['type_semaine']);
        }
        if (!empty($parametres['type_periode'])) {
            $this->queryBuilder->set('type_periode', ':type_periode');
            $this->queryBuilder->setParameter(':type_periode', $parametres['type_periode']);
        }
        if (!empty($parametres['debut'])) {
            $this->queryBuilder->set('debut', ':debut');
            $this->queryBuilder->setParameter(':debut', $parametres['debut']);
        }
        if (!empty($parametres['fin'])) {
            $this->queryBuilder->set('fin', ':fin');
            $this->queryBuilder->setParameter(':fin', $parametres['fin']);
        }
    }
}
