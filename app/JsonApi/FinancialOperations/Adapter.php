<?php

namespace App\JsonApi\FinancialOperations;

use App\FinancialOperation;
use App\FundsTransfer;
use App\Jobs\InitiateFundsTransfer;
use App\JsonApi\JsonApiSettings;
use CloudCreativity\LaravelJsonApi\Eloquent\AbstractAdapter;
use CloudCreativity\LaravelJsonApi\Pagination\StandardStrategy;
use Illuminate\Support\Collection;

class Adapter extends AbstractAdapter
{
    protected $defaultPagination = [
        'number' => 1,
        'size' => JsonApiSettings::DEFAULT_PAGE_SIZE,
    ];

    public function __construct(StandardStrategy $paging)
    {
        parent::__construct(new FinancialOperation(), $paging);
    }

    protected function filter($query, Collection $filters)
    {
        $user = $filters->get('user');
        if ($user) {
            $query->where('user_id', $user);
        }
    }

    protected function user()
    {
        return $this->belongsTo();
    }

    protected function counterparty_user()
    {
        return $this->belongsTo();
    }
}
