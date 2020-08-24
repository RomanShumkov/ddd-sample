<?php

use CloudCreativity\LaravelJsonApi\Routing\RouteRegistrar;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use CloudCreativity\LaravelJsonApi\Facades\JsonApi;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

/**
 * @OA\Info(title="DDD Sample App JSON API", version="0.1")
 */
JsonApi::register('default')->withNamespace('Api')->routes(function (RouteRegistrar $api) {
    /**
     * @OA\Post(
     *     path="/api/v1/funds-transfers",
     *     summary="Transfer funds",
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/FundsTransfer"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New user created successfully",
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/FundsTransfer"))
     *         )
     *     ),
     * )
     * //TODO: document error responses
     */
    /**
     * @OA\Get(
     *     path="/api/v1/funds-transfers/{id}",
     *     summary="Get latest data of funds transfer",
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         @OA\Schema(
     *             type="string",
     *             example="1"
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/FundsTransfer"))
     *         )
     *     ),
     * )
     * //TODO: document error responses
     */
    $api->resource('funds-transfers')
        ->controller()
        ->authorizer('default')
        ->only('create', 'read')
    ;

    /**
     * @OA\Post(
     *     path="/api/v1/users",
     *     summary="Create new user",
     *     security={},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/User"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New user created successfully",
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/User"))
     *         )
     *     ),
     * )
     * //TODO: document error responses
     */
    $api->resource('users')
        ->only('create')
    ;

    /**
     * @OA\Post(
     *     path="/api/v1/access-tokens",
     *     summary="Create new access token",
     *     security={},
     *     @OA\RequestBody(
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/AccessToken"))
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="New access token created successfully",
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/AccessToken"))
     *         )
     *     ),
     * )
     * //TODO: document error responses
     */
    $api->resource('access-tokens')
        ->only('create')
        ->controller()
    ;

    /**
     * @OA\Get(
     *     path="/api/v1/financial-operations",
     *     summary="Get financial operations",
     *     @OA\Parameter(
     *         name="filter",
     *         in="query",
     *         style="deepObject",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="user", ref="#/components/schemas/FinancialOperation.query.filter.user"),
     *         )
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         style="deepObject",
     *         @OA\Schema(
     *             type="object",
     *             @OA\Property(property="number", ref="#/components/schemas/FinancialOperation.query.page.number"),
     *             @OA\Property(property="size", ref="#/components/schemas/FinancialOperation.query.page.size"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="OK",
     *         @OA\MediaType(
     *             mediaType=JsonApiMediaType,
     *             @OA\Schema(@OA\Property(property="data", ref="#/components/schemas/FinancialOperation"))
     *         )
     *     ),
     * )
     * //TODO: document error responses
     */
    $api->resource('financial-operations')
        ->only('index')
        ->authorizer('default')
        ->controller()
    ;
});
