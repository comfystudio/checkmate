<?php

/** RouteConfig */

/**
 * Route Name
 * Route URL
 * Route Area
 * Route Controller
 * Route Action
 */

// Set Array
$routeArray = array();
$routeArray[] = Route::addRoute("Login",
                 "/backoffice/login",
                 "backoffice",
                 "adminUsers",
                 "login"
);

$routeArray[] = Route::addRoute("Login",
                 "/backoffice/logout",
                 "backoffice",
                 "adminUsers",
                 "logout"
);

$routeArray[] = Route::addRoute("Login",
                 "/login",
                 NULL,
                 "users",
                 "login"
);

$routeArray[] = Route::addRoute("Login",
                 "/logout",
                 NULL,
                 "users",
                 "logout"
);

