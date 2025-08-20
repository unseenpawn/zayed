<?php

use App\Controllers\PageController;
use App\Controllers\ContactController;
use App\Controllers\SearchController;

$r->addRoute(['GET','POST'], '/{lang:de|ar}/kontakt', [ContactController::class, 'form']);
$r->addRoute('GET', '/{lang:de|ar}/search', [SearchController::class, 'search']);
$r->addRoute('GET', '/{lang:de|ar}', [PageController::class, 'show']);
$r->addRoute('GET', '/{lang:de|ar}/{slug}', [PageController::class, 'show']);
