<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ContractController extends Controller
{
    public function show(Request $request)
    {
        $validatedData = $request->validate([
            'name' => ['required', 'exists:contracts,institution'],
            'interval' => ['sometimes', 'in:1,3,6,12']
        ]);

        $institution = Contract::where('institution', '=', $request->name)->limit('1')->first();

        $timeFilter = 1;

        if ($request->has('interval')) {
            $timeFilter = $request->interval;
        }


        $contractsCount = Contract::where('institution', '=', $request->name)
            ->whereNotNull('published_at')
            ->whereDate('published_at', '>=', Carbon::today()->subMonths($timeFilter))->count();

        $contracts = Contract::where('institution', '=', $request->name)
            ->whereDate('opened_at', '>=', Carbon::today()->subMonths($timeFilter))->paginate();

        $stats = DB::table('contracts')
            ->select(DB::raw("count(*) as contracts, TO_CHAR(published_at, 'YYYY-MM-Mon') as month, SUM (contract_amount) AS total"))
            ->where('institution', '=', $request->name)
            ->whereNotNull('published_at')
            ->whereDate('published_at', '>=', Carbon::today()->subMonths($timeFilter))
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $contractTypes = DB::table('contracts')
            ->select(DB::raw("count(*) as contracts, lower(procedure_type) as procedure"))
            ->where('institution', '=', $request->name)
            ->whereNotNull('published_at')
            ->whereNotNull('procedure_type')
            ->whereDate('published_at', '>=', Carbon::today()->subMonths($timeFilter))
            ->groupBy(['procedure'])
            ->get();



        $response = [
            "dependence" => [
                "name" => $institution->institution,
                "acronyms" => $institution->acronyms,
                "contracts_count" =>  $contractsCount,
                "contracts" => $contracts,
                "stats" => $stats,
                "contact_types" => $contractTypes
            ]
        ];
        return $response;
    }

    public function contracts()
    {
        return 'this endpoint is currently in progress';
    }
}
