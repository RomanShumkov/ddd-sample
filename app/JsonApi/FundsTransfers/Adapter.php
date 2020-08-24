<?php

namespace App\JsonApi\FundsTransfers;

use App\FundsTransfer;
use App\Jobs\InitiateFundsTransfer;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new FundsTransfer(), $paging);
    }

    protected function filter($query, Collection $filters)
    {
        $this->filterWithScopes($query, $filters);
    }

    protected function origin()
    {
        return $this->belongsTo();
    }

    protected function destination()
    {
        return $this->belongsTo();
    }

    protected function creating(FundsTransfer $fundsTransfer, $resource)
    {
        $fundsTransfer->setAttribute($fundsTransfer->getKeyName(), $resource['id']);
    }

    protected function created(FundsTransfer $fundsTransfer)
    {
        InitiateFundsTransfer::dispatch($fundsTransfer);
    }
}
