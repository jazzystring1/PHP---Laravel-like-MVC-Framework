<?php
require __DIR__ . '/vendor/autoload.php';

use App\Routing\Route;
use Barbet\Query\QueryBuilderHandler as Query;

//Auth
Route::set('/', 'LoginManager@index');
Route::set('/login', 'LoginManager@index');
Route::get('/login/{id}', 'LoginManager@test');
Route::post('/auth/login', 'LoginManager@login');

//Register
Route::set('/register', 'RegisterManager@index');
Route::post('/register/new', 'RegisterManager@register');

//Home
Route::set('/dashboard', 'HomeManager@index');
Route::set('/dashboard/logout', 'HomeManager@logout');

//Employee
Route::set('/manage', 'EmployeeManager@index');
Route::set('/manage/employee', 'EmployeeManager@index');
Route::set('/manage/employee/search', 'EmployeeManager@search');
Route::set('/manage/employee/new', 'EmployeeManager@show');
Route::get('/manage/employee/page/{id}', 'EmployeeManager@page');
Route::get('/manage/employee/id/{id}', 'EmployeeManager@profile');
Route::get('/manage/employee/search/{pattern}', 'EmployeeManager@search');
Route::post('/manage/employee/stats/template', 'EmployeeManager@stats');
Route::post('/manage/employee/stats/year', 'EmployeeManager@statsByYear');
Route::post('/manage/employee/stats/timeline', 'EmployeeManager@statsByTimeline');
Route::post('/manage/employee/search', 'EmployeeManager@search');
Route::post('/manage/employee/create', 'EmployeeManager@create');
Route::post('/manage/employee/update', 'EmployeeManager@update');
Route::post('/manage/employee/delete', 'EmployeeManager@delete');

//Vehicle
Route::set('/manage/vehicle', 'VehicleManager@index');
Route::get('/manage/vehicle/page/{id}', 'VehicleManager@page');
Route::get('/manage/vehicle/id/{id}', 'VehicleManager@profile');
Route::get('/manage/vehicle/search/{pattern}', 'VehicleManager@search');
Route::set('/manage/vehicle/new', 'VehicleManager@show');
Route::post('/manage/vehicle/create', 'VehicleManager@create');
Route::post('/manage/vehicle/update', 'VehicleManager@update');
Route::post('/manage/vehicle/delete', 'VehicleManager@delete');
Route::post('/manage/vehicle/stats/year', 'VehicleManager@statsByYear');
Route::post('/manage/vehicle/get', 'VehicleManager@get');
Route::post('/manage/vehicle/check', 'VehicleManager@check');

//Customer
Route::set('/manage/customer', 'CustomerManager@index');
Route::get('/manage/customer/page/{id}', 'CustomerManager@page');
Route::get('/manage/customer/id/{id}', 'CustomerManager@profile');
Route::set('/manage/customer/new', 'CustomerManager@show');
Route::post('/manage/customer/create', 'CustomerManager@create');
Route::post('/manage/customer/update', 'CustomerManager@update');
Route::post('/manage/customer/delete', 'CustomerManager@delete');
Route::post('/manage/customer/get', 'CustomerManager@get');
Route::get('/manage/customer/search/{pattern}', 'CustomerManager@search');

//Packages
Route::set('/manage/package', 'PackageManager@index');
Route::set('/manage/package/new', 'PackageManager@show');
Route::post('/manage/package/create', 'PackageManager@create');
Route::post('/manage/package/update', 'PackageManager@update');
Route::post('/manage/package/delete', 'PackageManager@delete');
Route::post('/manage/package/stats/timeline', 'PackageManager@statsByTimeline');

//Transactions
Route::set('/manage/transaction', 'TransactionManager@index');
Route::set('/manage/transaction/new', 'TransactionManager@show');
Route::get('/manage/transaction/page/{id}', 'TransactionManager@page');
Route::get('/manage/transaction/id/{id}', 'TransactionManager@profile');
Route::get('/manage/transaction/search/', 'TransactionManager@redirectSearch');
Route::get('/manage/transaction/search/{pattern}', 'TransactionManager@search');
Route::post('/manage/transaction/create', 'TransactionManager@create');
Route::post('/manage/transaction/done', 'TransactionManager@done');

//Reports
Route::get('/manage/report/statistics/page/{page}', 'ReportManager@statisticsDaily');
Route::get('/manage/report/statistics/from/{first}/to/{second}/page/{page}', 'ReportManager@statisticsDateRange');
Route::get('/manage/report/statistics/week/{week}/page/{page}', 'ReportManager@statisticsWeek');
Route::get('/manage/report/statistics/year/{year}/page/{page}', 'ReportManager@statisticsYear');
Route::get('/manage/report/statistics/month/{month}/year/{year}/page/{page}', 'ReportManager@statisticsMonth');
Route::get('/manage/report/salary/page/{page}', 'ReportManager@salaryPage');
Route::get('/manage/report/sales/page/{page}', 'ReportManager@salesPage');
Route::get('/manage/report/sales/from/{first}/to/{second}/page/{page}', 'ReportManager@salesDateRange');

Route::get('/tutorial', 'TutorialManager@index');

?>