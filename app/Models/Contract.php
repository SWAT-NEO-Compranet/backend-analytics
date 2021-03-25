<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contract extends Model
{
    use HasFactory;
    protected $dates = ['opened_at'];

    /**
     * Default filters for all queries
     *
     * @param [type] $query
     * @param [String] $acronym
     * @param [Number] $interval
     * @return void
     */
    public function scopeDefault($query, $acronym, $interval)
    {
        return $query
            ->whereNotNull('acronyms')
            ->whereNotNull('contract_init_date')
            ->whereNotNull('procedure_type')
            ->where('acronyms', '=', $acronym)
            ->whereDate('contract_init_date', '>=', Carbon::today()->subMonths($interval));
    }

    /**
     * Return all the stats for a given acronym
     *
     * @param [type] $query
     * @return void
     */
    public function scopeStats($query)
    {
        return $query
            ->select(
                DB::raw("count(*) as contracts, TO_CHAR(contract_init_date, 'YYYY-MM') as filter, TO_CHAR(contract_init_date, 'YYYY-Mon') as month, SUM (contract_amount) AS total")
            );
    }

    public function scopeTypes($query)
    {
        return $query
            ->select(DB::raw("count(*) as contracts, lower(procedure_type) as procedure"));
    }

    public function scopeCurrency($query)
    {
        return $query->select(DB::raw('count(*) as contracts, currency'));
    }
}
