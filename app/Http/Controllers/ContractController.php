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
            ->whereDate('opened_at', '>=', Carbon::today()->subMonths($timeFilter))->count();

        $contracts = Contract::where('institution', '=', $request->name)
            ->whereDate('opened_at', '>=', Carbon::today()->subMonths($timeFilter))->paginate();

        $stats = DB::table('contracts')
            ->select(DB::raw("count(*) as contracts, TO_CHAR(published_at, 'Mon') as month, SUM (contract_amount) AS total"))
            ->whereNotNull('published_at')
            ->whereDate('published_at', '>=', Carbon::today()->subMonths($timeFilter))
            ->groupBy('month')
            ->get();

        $response = [
            "dependence" => [
                "name" => $institution->institution,
                "acronyms" => $institution->acronyms,
                "contracts_count" =>  $contractsCount,
                "contracts" => $contracts,
                "stats" => $stats
            ]
        ];
        return $response;
    }

    public function contracts()
    {
        return 'this endpoint is currently in progress';
    }
}
