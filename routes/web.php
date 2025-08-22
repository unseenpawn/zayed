<?php

use App\Controllers\PageController;
use App\Controllers\ContactController;
use App\Controllers\SearchController;
use App\Controllers\AuthController;
use App\Controllers\AdminController;

$r->addRoute('GET', '/', function() {
    header('Location: /de');
    exit;
});
$r->addRoute(['GET','POST'], '/login', [AuthController::class, 'login']);
$r->addRoute('GET', '/logout', [AuthController::class, 'logout']);
$r->addRoute(['GET','POST'], '/admin/blog', [AdminController::class, 'blog']);
$r->addRoute(['GET','POST'], '/{lang:de|ar}/kontakt', [ContactController::class, 'form']);
$r->addRoute('GET', '/{lang:de|ar}/search', [SearchController::class, 'search']);
$r->addRoute('GET', '/{lang:de|ar}', [PageController::class, 'show']);
$r->addRoute('GET', '/{lang:de|ar}/{slug}', [PageController::class, 'show']);
