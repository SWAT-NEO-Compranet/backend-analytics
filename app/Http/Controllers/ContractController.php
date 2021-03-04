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
        ]);

        $institution = Contract::where('institution', '=', $request->name)->limit('1')->first();

        $contractsCount = Contract::where('institution', '=', $request->name)
            ->whereDate('opened_at', '>=', Carbon::today()->subMonths(3))->count();

        $contracts = Contract::where('institution', '=', $request->name)
            ->whereDate('opened_at', '>=', Carbon::today()->subMonths(3))->paginate();

        $response = [
            "dependence" => [
                "name" => $institution->institution,
                "acronyms" => $institution->acronyms,
                "contracts_count" =>  $contractsCount,
                "contracts" => $contracts
            ]
        ];
        return $response;
    }

    public function contracts()
    {
        return 'this endpoint is currently in progress';
    }
}
