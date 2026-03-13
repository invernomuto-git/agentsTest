<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Root redirect to contacts list
$routes->get('/', 'Contatti::index');

// Contacts CRUD
$routes->get('contatti',              'Contatti::index');
$routes->get('contatti/create',       'Contatti::create');
$routes->post('contatti/create',      'Contatti::create');
$routes->get('contatti/edit/(:num)',  'Contatti::edit/$1');
$routes->post('contatti/edit/(:num)', 'Contatti::edit/$1');
$routes->get('contatti/delete/(:num)',  'Contatti::delete/$1');
$routes->post('contatti/delete/(:num)', 'Contatti::delete/$1');

// Companies CRUD
$routes->get('aziende',              'Aziende::index');
$routes->get('aziende/create',       'Aziende::create');
$routes->post('aziende/create',      'Aziende::create');
$routes->get('aziende/edit/(:num)',  'Aziende::edit/$1');
$routes->post('aziende/edit/(:num)', 'Aziende::edit/$1');
$routes->get('aziende/delete/(:num)',  'Aziende::delete/$1');
$routes->post('aziende/delete/(:num)', 'Aziende::delete/$1');

