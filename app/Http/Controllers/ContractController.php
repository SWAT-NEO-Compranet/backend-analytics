<?php

namespace App\Http\Controllers;

use App\Models\Contract;
use App\Http\Requests\ContractsQueryRequest;


/**
 * Get contract details
 * @OA\Info(title="Swat Analytics", version="1.0")
 * @OA\Server(url="https://neo-analytics-backend.herokuapp.com")
 * @param ContractsQueryRequest $request
 * @return void
 */
class ContractController extends Controller
{
    /**
     * @OA\Post(
     * path="/api/dependencies/details",
     * summary="Dependence details",
     * description="Get the details for a given dependence",
     * tags={"Contracts"},
     *      @OA\RequestBody(
     *          required=true,
     *          description="Name for get the dependence details",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="CFE"),
     *              @OA\Property(property="interval", type="number", example="12"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong name or interval information",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      collectionFormat="multi",
     *                      @OA\Items(
     *                          type="string",
     *                          example="The name field is required."
     *                      ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success response",
     *          @OA\JsonContent(
     *              @OA\Property(property="name", type="string", example="Comisión Federal de Electricidad."),
     *              @OA\Property(property="acronyms", type="string", example="CFE"),
     *              @OA\Property(property="contracts_count", type="number", example="1000"),
     *              @OA\Property(property="contracts", type="string", example=""),
     *              @OA\Property(property="contact_types", type="string", example=""),
     *              @OA\Property(
     *                  property="stats",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="contracts",
     *                          type="number",
     *                          example="500"
     *                      ),
     *                      @OA\Property(
     *                          description="Year and month",
     *                          type="string",
     *                          property="filter",
     *                          example="2018-03"
     *                      ),
     *                      @OA\Property(
     *                          description="Month",
     *                          type="string",
     *                          property="month",
     *                          example="May"
     *                      ),
     *                      @OA\Property(
     *                          description="Total",
     *                          type="string",
     *                          property="total",
     *                          example="2228520826.6436996"
     *                      ),
     *                  ),
     *              ),
     *              @OA\Property(
     *                  property="contract_types",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="contracts",
     *                          type="number",
     *                          example="500"
     *                      ),
     *                      @OA\Property(
     *                          description="Procedure type",
     *                          type="string",
     *                          property="procedure",
     *                          example="adjudicación directa federal"
     *                      ),
     *                  ),
     *              )
     *          )
     *      )
     * )
     */
    public function show(ContractsQueryRequest $request)
    {
        $name = $request->name;
        $timeFilter = 1;

        $institution = Contract::where('acronyms', '=', $request->name)->limit('1')->first();

        if ($request->has('interval')) {
            $timeFilter = $request->interval;
        }

        $contracts = Contract::default($name, $timeFilter)->paginate();
      
        $contracts->setPath('https://neo-analytics-backend.herokuapp.com/api/dependencies/details');

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

    /**
     * @OA\Post(
     * path="/api/stats/contracts/generals",
     * summary="Dependence general stats",
     * tags={"Contracts"},
     * description="Get the general statistics for a given dependence",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Name for get the dependence details",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="CFE"),
     *              @OA\Property(property="interval", type="number", example="12"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=422,
     *          description="Wrong name or interval information",
     *          @OA\JsonContent(
     *              @OA\Property(property="message", type="string", example="The given data was invalid."),
     *              @OA\Property(
     *                  property="errors",
     *                  type="object",
     *                  @OA\Property(
     *                      property="name",
     *                      type="array",
     *                      collectionFormat="multi",
     *                      @OA\Items(
     *                          type="string",
     *                          example="The name field is required."
     *                      ),
     *                  ),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success response",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="stats",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="contracts",
     *                          type="number",
     *                          example="500"
     *                      ),
     *                      @OA\Property(
     *                          description="Year and month",
     *                          type="string",
     *                          property="filter",
     *                          example="2018-03"
     *                      ),
     *                      @OA\Property(
     *                          description="Month",
     *                          type="string",
     *                          property="month",
     *                          example="May"
     *                      ),
     *                      @OA\Property(
     *                          description="Total",
     *                          type="string",
     *                          property="total",
     *                          example="2228520826.6436996"
     *                      ),
     *                  ),
     *              ),
     *          )
     *      )
     * )
     * */
    public function contractGenerals(ContractsQueryRequest $request)
    {
        $response = Contract::stats()->default($request->name, $request->interval)->groupBy(['filter', 'month'])->orderBy('filter')->get();

        return ["stats" => $response];
    }

    /**
     * @OA\Post(
     * path="/api/stats/contracts/types",
     * summary="Dependence type stats",
     * tags={"Contracts"},
     * description="Get the statistics filtered by type for a given dependence",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Name for get the dependence details",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="CFE"),
     *              @OA\Property(property="interval", type="number", example="12"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success response",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="contract_types",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="contracts",
     *                          type="number",
     *                          example="500"
     *                      ),
     *                      @OA\Property(
     *                          description="Procedure type",
     *                          type="string",
     *                          property="procedure",
     *                          example="adjudicación directa federal"
     *                      ),
     *                  ),
     *              )
     *          )
     *      )
     * )
     */
    public function contractTypes(ContractsQueryRequest $request)
    {
        $response = Contract::types()->default($request->name, $request->interval)->groupBy(['procedure'])->get();
        return ["contact_types" => $response];
    }

    /**
     * @OA\Post(
     * path="/api/stats/contracts/currency",
     * summary="Dependence currency stats",
     * tags={"Contracts"},
     * description="Get the statistics filtered by currency for a given dependence",
     *      @OA\RequestBody(
     *          required=true,
     *          description="Name for get the dependence details",
     *          @OA\JsonContent(
     *              required={"name"},
     *              @OA\Property(property="name", type="string", example="CFE"),
     *              @OA\Property(property="interval", type="number", example="12"),
     *          ),
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success response",
     *          @OA\JsonContent(
     *              @OA\Property(
     *                  property="contract_currencies",
     *                  type="array",
     *                  collectionFormat="multi",
     *                  @OA\Items(
     *                      @OA\Property(
     *                          property="contracts",
     *                          type="number",
     *                          example="500"
     *                      ),
     *                      @OA\Property(
     *                          description="Contracts currency",
     *                          type="string",
     *                          property="currency",
     *                          example="MXN"
     *                      ),
     *                  ),
     *              )
     *          )
     *      )
     * )
     */
    public function contractCurrency(ContractsQueryRequest $request)
    {
        $response = Contract::currency()->default($request->name, $request->interval)->groupBy('currency')->get();
        return ["contract_currencies" => $response];
    }
}
