<?php

namespace App\Providers;

use App\Domain\FundsTransferring\Transaction\Transaction;
use App\Domain\FundsTransferring\Transaction\TransactionRepository;
use App\Domain\FundsTransferring\Account\Account;
use App\Domain\FundsTransferring\Account\AccountRepository;
use App\Listeners\UpdateReportingModelWhenFundsTransferCompleted;
use App\Services\FundsTransferring\FundsTransferringEventListener;
use App\Services\FundsTransferring\FundsTransferringService;
use App\Services\FundsTransferring\FundsTransferSnapshotFactory;
use App\Services\FundsTransferring\Implementation\FundsTransferrer;
use App\Services\FundsTransferring\UserRegistrationEventSubscriber;
use App\Transactions\FundsTransferring\TransactionalFundsTransferring;
use App\Transactions\FundsTransferring\TransactionalUserRegistrationSubscriber;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Illuminate\Contracts\Container\Container;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(FundsTransferringService::class, FundsTransferrer::class);
        $this->app->bind(UserRegistrationEventSubscriber::class, FundsTransferrer::class);
        $this->app->bind(FundsTransferSnapshotFactory::class, UpdateReportingModelWhenFundsTransferCompleted::class);
        $this->app->bind(FundsTransferringEventListener::class, UpdateReportingModelWhenFundsTransferCompleted::class);
        $this->app->singleton(FundsTransferrer::class);

        $doctrineRepositories = [
            AccountRepository::class => Account::class,
            TransactionRepository::class => Transaction::class,
        ];
        foreach ($doctrineRepositories as $repositoryType => $entityType) {
            $this->registerDoctrineRepository($repositoryType, $entityType);
        }

        $transactionalServices = [
            FundsTransferringService::class => TransactionalFundsTransferring::class,
            UserRegistrationEventSubscriber::class => TransactionalUserRegistrationSubscriber::class,
        ];
        foreach ($transactionalServices as $contractType => $decoratorType)
        {
            $this->decorateTransactional($contractType, $decoratorType);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    private function registerDoctrineRepository(string $repositoryClass, string $entityClass)
    {
        $this->app->singleton($repositoryClass, function (Container $app) use ($entityClass) {
            return $app->get(EntityManager::class)->getRepository($entityClass);
        });
    }

    private function decorateTransactional($serviceClass, $decoratorClass)
    {
        $this->app->extend($serviceClass, function ($service, Container $app) use ($decoratorClass) {
            return new $decoratorClass($service, $app->get(EntityManager::class));
        });
    }
}
