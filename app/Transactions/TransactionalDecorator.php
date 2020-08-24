<?php
declare(strict_types=1);

namespace App\Transactions;

use App\Transactions\FundsTransferring\TransactionalFundsTransferring;
use Doctrine\DBAL\LockMode;
use Doctrine\ORM\EntityManager;

/**
 * Layer superclass for transactional decorators
 *
 * Transactional decorators are wrapping pure PHP objects from service layer in order to shield them
 * from concurrent database access aspects.
 *
 * Once entities are locked by transactional decorators, they are placed in unit of work and later on when
 * service layer implementation is querying same objects using entity repositories, entities are being returned
 * from in-memory unit of work avoiding redundant database queries.
 *
 * @see TransactionalFundsTransferring as an example of how transactions and locking can be added transparantly
 * @see https://en.wikipedia.org/wiki/ACID
 * @see https://www.martinfowler.com/eaaCatalog/layerSupertype.html
 * @see https://martinfowler.com/eaaCatalog/unitOfWork.html
 * @see https://en.wikipedia.org/wiki/Decorator_pattern
 */
abstract class TransactionalDecorator
{
    protected object $implementation;

    protected EntityManager $persistence;

    public function __construct(object $implementation, EntityManager $persistence)
    {
        $this->implementation = $implementation;
        $this->persistence = $persistence;
    }

    protected function transactional(string $methodName, array $arguments, callable $locker = null)
    {
        return $this->persistence->transactional(
            function () use ($methodName, $arguments, $locker) {
                $locker ? $locker() : null;

                return call_user_func_array(
                    array($this->implementation, $methodName),
                    $arguments
                );
            }
        );
    }

    protected function lockForUpdate($entityClass, $id)
    {
        $this->persistence->getRepository($entityClass)->find($id, LockMode::PESSIMISTIC_WRITE);
    }
}
