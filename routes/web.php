<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', 'HomeController@index')->name('home');
Route::get('check_register/{id}', 'StudentController@index');
Route::get('mark_attendance/{id}', 'StudentController@mark_attendance');
Route::post('student_registration/{id}', 'StudentController@insert');
Route::get('check_feedback/{id}', 'StudentController@check_feedback');
Route::get('feedback/{id}', 'StudentController@feedback');
Route::post('submit_feedback/{id}', 'StudentController@submit_feedback');

Auth::routes();
Route::get('logout', 'Auth\LoginController@logout')->name('logout');

Route::group([
    'middleware' => 'auth'
], function () {
Route::match(array('GET', 'POST'), 'home', 'HomeController@index')->name('home');
//Route::get('home', 'HomeController@index')->name('home');
Route::get('save_date', 'HomeController@save_date');
Route::get('pile_excel', 'HomeController@dashboard_excel');
Route::get('send_mail', 'HomeController@send_mail');

Route::get('reconcilation', 'HomeController@dummy');
Route::get('timeline', 'HomeController@dummy');
Route::get('andriod_ios', 'HomeController@dummy');
Route::get('create_project', 'HomeController@dummy');
Route::get('create_project_goods', 'HomeController@dummy');
Route::get('andriod_ios_goods', 'HomeController@dummy');

/*--------------------- ADMIN ----------------------------*/

/* -------------   Client Controller --------------- */
Route::get('clients', 'CompanyController@index');
Route::get('add_client', 'CompanyController@add');
Route::post('add_client', 'CompanyController@insert_company');
Route::get('edit_client/{id}', 'CompanyController@edit');
Route::get('change_status/{id}/{status}', 'CompanyController@change_status');
Route::post('update_client', 'CompanyController@update');

/* -------------   Staff Controller --------------- */

Route::get('staff', 'StaffController@index');
Route::get('add_staff', 'StaffController@add');
Route::post('add_staff', 'StaffController@insert');
Route::get('edit_staff/{id}', 'StaffController@edit');
Route::post('update_staff', 'StaffController@update');
Route::get('change_staff_status/{id}/{status}', 'StaffController@change_status');

/*--------------------- Company ----------------------------*/

/* -------------   Client User Controller --------------- */
Route::get('my_users', 'UserController@index');
Route::get('add_user', 'UserController@add');
Route::post('add_user', 'UserController@insert');
Route::get('edit_user/{id}', 'UserController@edit');
Route::post('update_user', 'UserController@update');
Route::get('change_user_status/{id}/{status}', 'UserController@change_status');


/* -------------   Location Controller --------------- */
Route::get('locations', 'LocationController@index');
Route::get('add_location', 'LocationController@add');
Route::post('add_location', 'LocationController@insert');
Route::get('edit_location/{id}', 'LocationController@edit');
Route::post('update_location', 'LocationController@update');
Route::get('change_location_status/{id}/{status}', 'LocationController@change_status');


/* -------------   Sites Controller --------------- */
Route::get('sites', 'SitesController@index');
Route::get('add_site', 'SitesController@add');
Route::post('add_site', 'SitesController@insert');
Route::get('edit_site/{id}', 'SitesController@edit');
Route::post('update_site', 'SitesController@update');
Route::get('change_site_status/{id}/{status}', 'SitesController@change_status');

/* -------------   Piles Controller --------------- */
Route::match(array('GET', 'POST'), 'piles', 'PilesController@index');
Route::get('add_pile', 'PilesController@add');
Route::post('add_pile', 'PilesController@insert');
Route::get('add_bulk_pile', 'PilesController@add_bulk_pile');
Route::post('add_bulk_pile', 'PilesController@upload_bulk_piles');
Route::get('edit_pile/{id}', 'PilesController@edit');
Route::post('update_pile', 'PilesController@update');
Route::get('change_pile_status/{id}/{status}', 'PilesController@change_status');
Route::get('get_sites', 'PilesController@get_sites');
Route::get('load_images', 'PilesController@load_images');
Route::get('advance_report', 'PilesController@advance_report');
Route::get('view_advance_report/{id}', 'PilesController@view_advance_report');
Route::post('save_share_image', 'PilesController@save_share_image');
Route::post('share_advance_report', 'PilesController@share_advance_report');

/* -------------   Bulk Material Controller --------------- */
Route::get('bulk_material', 'BulkMaterialController@index');
Route::get('add_bulk_material', 'BulkMaterialController@add');
Route::post('add_bulk_material', 'BulkMaterialController@insert');
Route::get('edit_bulk_material/{id}', 'BulkMaterialController@edit');
Route::post('update_bulk_material', 'BulkMaterialController@update');
Route::get('change_bulk_material_status/{id}/{status}', 'BulkMaterialController@change_status');

/* -------------   Staff Login --------------- */
Route::get('dates_metron', 'DateMetronController@index');
Route::get('add_date_metron', 'DateMetronController@add');
Route::post('add_date_metron', 'DateMetronController@insert');
Route::get('edit_date_metron/{id}', 'DateMetronController@edit');
Route::post('update_date_metron', 'DateMetronController@update');
Route::get('get_staff_sites', 'DateMetronController@get_staff_sites');
Route::get('get_staff_piles', 'DateMetronController@get_staff_piles');
Route::get('add_bulk_date_metron', 'DateMetronController@add_bulk_date_metron');
Route::post('add_bulk_date_metron', 'DateMetronController@upload_bulk_date_metron');



Route::get('get_states', 'CompanyController@get_states');

});