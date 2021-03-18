<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function show(Request $request)
    {
        $request->validate([
            'name' => ['required', 'exists:contracts,institution'],
            'interval' => ['sometimes', 'in:1,3,6,12,24,48,60']
        ]);

        $name = $request->name;
        $timeFilter = 1;

        $institution = Contract::where('institution', '=', $request->name)->limit('1')->first();

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

    public function contracts()
    {
        return 'this endpoint is currently in progress';
    }
}
