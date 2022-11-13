<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->group([
    'prefix' => 'api/user'
], function ($router) {
    /*
     * User
     * */
    $router->group(['middleware' => 'auth:api'], function ($router) {
        $router->post('logout', 'AuthController@logout');
        $router->post('refresh', 'AuthController@refresh');
        $router->post('me', 'AuthController@me');

        /*
         * Company
         * */
        $router->get('companies', 'CompanyController@companyByUser');
        $router->post('companies', 'CompanyController@store');
    });
    /*
     * Auth
     * */
    $router->post('register', 'AuthController@register');
    $router->post('sign-in', 'AuthController@login');
    $router->post('forgot-password', 'AuthController@forgotPassword');

    /*
     * Auth reset
     * */
    $router->get('/reset-password/{token}', ['as' => 'password.reset', function ($token) {
        return response()->json($token);
    }]);
    $router->post('/reset-password', 'AuthController@resetPassword');

});
