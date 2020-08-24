<?php
declare(strict_types=1);

namespace App\Persistence\Doctrine;

use Doctrine\ORM\EntityRepository;

/**
 * Layer supertype for Doctrine data mappers
 * @see https://www.martinfowler.com/eaaCatalog/layerSupertype.html
 * @see https://martinfowler.com/eaaCatalog/dataMapper.html
 */
abstract class AbstractMapper extends EntityRepository
{
    protected function persist(object $entity)
    {
        $this->getEntityManager()->persist($entity);
    }
}
