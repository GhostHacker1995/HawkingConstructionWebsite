<?php
/**
 * Route table. Clean URLs are mapped to "Controller@action".
 * Trailing slashes are normalized away by the router, so both
 * /about and /about/ resolve to the same place.
 */

use App\Core\Router;

return function (Router $r): void {
    // --- Home ---
    $r->get('/', 'HomeController@index');

    // --- Top-level pages ---
    $r->get('/about', 'PageController@about');
    $r->get('/services', 'PageController@services');
    $r->get('/projects', 'PageController@projects');
    $r->get('/hse', 'PageController@hse');
    $r->get('/leadership', 'PageController@leadership');

    // --- Contact ---
    $r->get('/contact', 'ContactController@index');
    $r->post('/contact', 'ContactController@submit');
};
