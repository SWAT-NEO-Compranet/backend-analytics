<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Models\AmountsPerDependency;
use App\Http\Requests\ContractsQueryRequest;

class ContractController extends Controller
{
    public function show(ContractsQueryRequest $request)
    {
        $name = $request->name;
        $timeFilter = 1;

        $institution = Contract::where('acronyms', '=', $request->name)->limit('1')->first();

        if ($request->has('interval')) {
            $timeFilter = $request->interval;
        }

        $contracts = Contract::default($name, $timeFilter)->paginate();

        $stats = Contract::stats()->default($name, $timeFilter)->groupBy(['filter', 'month'])->orderBy('filter')->get();

        $contractTypes = Contract::types()->default($name, $timeFilter)->groupBy(['procedure'])->get();

        $response = [
            "dependence" => [
                "name" => $institution->institution,
                "acronyms" => $institution->acronyms,
                "contracts_count" =>  $contracts->total(),
                "contracts" => $contracts,
                "stats" => $stats,
                "contact_types" => $contractTypes
            ]
        ];
        return $response;
    }

    public function contractGenerals(ContractsQueryRequest $request)
    {
        $response = Contract::stats()->default($request->name, $request->interval)->groupBy(['filter', 'month'])->orderBy('filter')->get();

        return ["stats" => $response];
    }

    public function contractTypes(ContractsQueryRequest $request)
    {
        $response = Contract::types()->default($request->name, $request->interval)->groupBy(['procedure'])->get();
        return ["contact_types" => $response];
    }


    public function contractCurrency(ContractsQueryRequest $request)
    {
        $response = Contract::currency()->default($request->name, $request->interval)->groupBy('currency')->get();
        return ["contract_currencies" => $response];
    }
}
