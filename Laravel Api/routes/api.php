<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Testhada;
use App\Http\Controllers\apiController;

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

Route::group(['middleware' => 'cors'], function () {

    Route::get('/Dashboard', Testhada::class);
    Route::get('/getusers', [Testhada::class,'GetUsers']);
    Route::get('/adduser',[Testhada::class,'addUser']);
    Route::get('/UpdateUser',[Testhada::class,'UpdateUser']);
    Route::get('/UserById',[Testhada::class,'GetUserById']);
    Route::get('/deleteUser',[Testhada::class,'deleteUser']);
    Route::get('/clientcommandes',[Testhada::class,'Clientcommandes']);
    Route::get('/profiledetails',[Testhada::class,'ProfileDetails']);
    Route::get('/details',[Testhada::class,'detstest']);
    Route::get('/commandeDetails',[Testhada::class,'CommandeDetails']);
    Route::get('/products',[Testhada::class,'getProducts']);
    Route::get('/catsfrnss',[Testhada::class,'getCatsFrnss']);
    Route::get('/addproduct',[Testhada::class,'addProduct']);
    Route::get('/updateproduct',[Testhada::class,'editProduct']);
    Route::get('/deleteproduct',[Testhada::class,'deleteProduct']);
    Route::get('/producthistory',[Testhada::class,'productHistory']);
    Route::get('/addcat',[Testhada::class,'addCat']);

    Route::get('/addfrns',[Testhada::class,'addFrns']);
    Route::get('/getfrnss',[Testhada::class,'getFrnss']);
    Route::get('/updatefrns',[Testhada::class,'UpdateFrns']);
    Route::get('/deletefrns',[Testhada::class,'DeleteFrns']);
    Route::get('/frnsprofile',[Testhada::class,'FrnsProfile']);

    Route::get('/getpayments',[Testhada::class,'GetPayments']);
    Route::get('/addpayment',[Testhada::class,'AddPayment']);
    Route::get('/updatepayment',[Testhada::class,'UpdatePayment']);

    Route::get('/banques',[Testhada::class,'GetBanques']);
    Route::get('/addbanque',[Testhada::class,'AddBanque']);


    Route::get('/employeeprofile',[Testhada::class,'EmployeeProfile']);
    Route::get('/addemployee',[Testhada::class,'AddEmployee']);
    Route::get('/getemployees',[Testhada::class,'GetEmployees']);
    Route::get('/addemployeepayment',[Testhada::class,'AddEmployeePayment']);


    Route::get('/getcommandes',[Testhada::class,'GetCommandes']);
    Route::get('/addnewcommande',[Testhada::class,'AddNewCommande']);
    Route::get('/updatecommande',[Testhada::class,'UpdateCommande']);
    Route::get('/deletecommande',[Testhada::class,'DeleteCommande']);



    Route::get('/prdctsdets',[Testhada::class,'ProductsDets']);



    Route::get('/newcommandeProdutsClients',[Testhada::class,'NewCommandeProductsClients']);
    Route::get('/seeddb',[Testhada::class,'SeedDb']);



    //Route::get('/getusers', 'apiController@getUsers');
    Route::get('/testing',[Testhada::class,'testing']);


});
