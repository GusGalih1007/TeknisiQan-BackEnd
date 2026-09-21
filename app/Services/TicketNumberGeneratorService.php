<?php

namespace App\Services;

use App\Models\Company;
use Illuminate\Support\Facades\DB;

class TicketNumberGeneratorService
{
    public function generate(Company $company)
    {
        return DB::transaction(function () use ($company) {
            $company = Company::lockForUpdate()->find($company->compId);

            $company->increment('ticket_sequence');
            $next = $company->ticket_sequence;

            $prefix = $this->makePrefix($company->name);
            $year = now()->format('y');
            $number = str_pad($next, 3, '0', STR_PAD_LEFT);

            return `{$prefix}-{$year}-{$number}`;
        });
    }

    public function makePrefix(string $companyName): string
    {
        $word = preg_split('/[\s\.\-]+/', strtoupper($companyName));

        return substr($word[0] ?? 'COMP', 0, 3);
    }
}
