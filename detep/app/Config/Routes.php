<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
// $routes->set404Override();
$routes->set404Override(function(){
    return view('404');
});
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'Home::index', ['filter' => 'noauth']);
$routes->post('login', 'Home::auth', ['filter' => 'noauth']);
$routes->get('logout', 'Home::logout');
$routes->get('app', 'Home::getApp');
$routes->get('pdf', 'Parcel::fpdf');

$routes->group('dashboard', ['filter' => 'auth'], function($routes)
{
	$routes->get('/', 'Dashboard::index');
	$routes->match(['get', 'post'], 'intermediaries', 'Intermediary::index', ["as" => "intermediaries_page"]);
	$routes->post('intermediaries/delete', 'Intermediary::deleteIntermediary');
	$routes->match(['get', 'post'],'stations', 'Station::index');
	$routes->post('stations/delete', 'Station::deleteStation');
	$routes->match(['get', 'post'],'levels', 'Level::index');
	$routes->match(['get', 'post'],'products', 'Product::index');
	$routes->post('products/delete', 'Product::deleteProduct');
	$routes->match(['get', 'post'],'parcels', 'Parcel::index');
	$routes->post('parcels/parcel-path', 'Parcel::parcelPath');
	$routes->post('parcels/send', 'Parcel::sendPercel');
	$routes->post('parcels/delete', 'Parcel::deleteParcel');
	$routes->post('parcels/move', 'Parcel::moveParcel');
	$routes->get('print/(:segment)', 'Parcel::printFile/$1');
	$routes->post('reset', 'Parcel::reset');
	
	
	$routes->get('test', 'Parcel::test');
});

$routes->group("api", function ($routes) {
	
	$routes->get("beacons", "Api::getBeacons");
	$routes->post("check-pro", "Api::checkIfPro");
	$routes->post("submit-pro", "Api::submitPro");
	$routes->post("reset-pro", "Api::resetPro");
	$routes->get("immediate-stations/(:segment)", "Api::immediateStations/$1");
	$routes->get("parcels-to-receive/(:segment)", "Api::getParcelsToReceive/$1");
	$routes->get("parcels-to-send/(:segment)", "Api::getParcelsToSend/$1");
	$routes->post("detect-product", "Api::detectProduct");
});

$routes->environment('development', function($routes)
{
    $routes->get('make-migration', 'Migrate::index');
    $routes->get('make-seed', 'Seed::index');
    $routes->get('merge', 'Parcel::mergeImages');
});

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
