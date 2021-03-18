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
            ->whereNotNull('published_at')
            ->whereNotNull('procedure_type')
            ->where('institution', '=', $acronym)
            ->whereDate('published_at', '>=', Carbon::today()->subMonths($interval));
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
                DB::raw("count(*) as contracts, TO_CHAR(published_at, 'YYYY-MM') as filter, TO_CHAR(published_at, 'Mon') as month, SUM (contract_amount) AS total")
            );
    }

    public function scopeTypes($query)
    {
        return $query
            ->select(DB::raw("count(*) as contracts, lower(procedure_type) as procedure"));
    }
}
