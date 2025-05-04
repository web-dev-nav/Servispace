<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
//Default route redirect
 $routes->get('/', 'Tech\AuthController::login');

// Admin routes
$routes->group('admin', function($routes) {
    $routes->get('login', 'Admin\AuthController::login');
    $routes->post('login', 'Admin\AuthController::attemptLogin');
    $routes->get('logout', 'Admin\AuthController::logout');
    
    // Protected routes (need authentication)
    $routes->group('', ['filter' => 'adminauth'], function($routes) {
        $routes->get('dashboard', 'Admin\DashboardController::index');
        // Add more protected routes here later
    });
});

// Organization routes
$routes->group('admin/organizations', ['filter' => 'adminauth'], function($routes) {
    $routes->get('/', 'Admin\OrganizationController::index');
    $routes->get('create', 'Admin\OrganizationController::create');
    $routes->post('store', 'Admin\OrganizationController::store');
    $routes->get('edit/(:num)', 'Admin\OrganizationController::edit/$1');
    $routes->post('update/(:num)', 'Admin\OrganizationController::update/$1');
    $routes->get('delete/(:num)', 'Admin\OrganizationController::delete/$1');
    $routes->post('upload-document/(:num)', 'Admin\OrganizationController::uploadDocument/$1');
    $routes->get('delete-document/(:num)/(:num)', 'Admin\OrganizationController::deleteDocument/$1/$2');
    $routes->get('view-document/(:num)/(:num)', 'Admin\OrganizationController::viewDocument/$1/$2');
});

// Technician routes
$routes->group('admin/technicians', ['filter' => 'adminauth'], function($routes) {
    $routes->get('/', 'Admin\TechnicianController::index');
    $routes->get('create', 'Admin\TechnicianController::create');
    $routes->post('store', 'Admin\TechnicianController::store');
    $routes->get('edit/(:num)', 'Admin\TechnicianController::edit/$1');
    $routes->post('update/(:num)', 'Admin\TechnicianController::update/$1');
    $routes->get('delete/(:num)', 'Admin\TechnicianController::delete/$1');
    $routes->get('profile/(:num)', 'Admin\TechnicianController::profile/$1');
});

// Ticket routes
$routes->group('admin/tickets', ['filter' => 'adminauth'], function($routes) {
    $routes->get('/', 'Admin\TicketController::index');
    $routes->get('create', 'Admin\TicketController::create');
    $routes->post('store', 'Admin\TicketController::store');
    $routes->get('view/(:num)', 'Admin\TicketController::view/$1');
    $routes->get('edit/(:num)', 'Admin\TicketController::edit/$1');
    $routes->post('update/(:num)', 'Admin\TicketController::update/$1');
    $routes->post('assign/(:num)', 'Admin\TicketController::assign/$1');
    $routes->get('unassign/(:num)', 'Admin\TicketController::unassign/$1');
    $routes->get('delete/(:num)', 'Admin\TicketController::delete/$1');
    $routes->get('attachment/(:num)', 'Admin\TicketController::viewAttachment/$1');
    $routes->get('delete-attachment/(:num)', 'Admin\TicketController::deleteAttachment/$1');
    
});


// Technician authentication routes
$routes->group('tech', function($routes) {
    $routes->get('login', 'Tech\AuthController::login');
    $routes->post('login', 'Tech\AuthController::attemptLogin');
    $routes->get('logout', 'Tech\AuthController::logout');
    $routes->get('forgot-password', 'Tech\AuthController::forgotPassword');
    $routes->post('forgot-password', 'Tech\AuthController::sendResetLink');
    $routes->get('reset-password/(:any)', 'Tech\AuthController::resetPassword/$1');
    $routes->post('reset-password', 'Tech\AuthController::updatePassword');
});

// Protected technician routes
$routes->group('tech', ['filter' => 'techauth'], function($routes) {
    $routes->get('dashboard', 'Tech\DashboardController::index');
    
    // Profile routes
    $routes->get('profile', 'Tech\ProfileController::index');
    $routes->post('profile/update', 'Tech\ProfileController::update');
    $routes->post('profile/change-password', 'Tech\ProfileController::changePassword');
    
    // More routes will be added here as we develop the technician portal
});

// Ticket routes for technicians
$routes->group('tech/tickets', ['filter' => 'techauth'], function($routes) {
    $routes->get('/', 'Tech\TicketController::index');
    $routes->get('view/(:num)', 'Tech\TicketController::view/$1');
    // Scheduling
    $routes->get('schedule/(:num)', 'Tech\TicketController::schedule/$1');
    $routes->post('save-schedule/(:num)', 'Tech\TicketController::saveSchedule/$1');
    // Service actions
    $routes->post('start/(:num)', 'Tech\TicketController::start/$1');
    $routes->post('complete/(:num)', 'Tech\TicketController::complete/$1');
    // Part management
    $routes->post('update-part/(:num)', 'Tech\TicketController::updatePart/$1');
    // Updates and comments
    $routes->post('add-update/(:num)', 'Tech\TicketController::addUpdate/$1');
    // Attachments
    $routes->get('attachment/(:num)', 'Tech\TicketController::attachment/$1');
});


// Ajax routes
$routes->get('admin/tickets/get-customers-by-organization', 'Admin\TicketController::getCustomersByOrganization', ['filter' => 'adminauth']);
$routes->get('admin/tickets/delete-part/(:num)/(:num)', 'Admin\TicketController::deletePart/$1/$2', ['filter' => 'adminauth']);