<?php

use Diglactic\Breadcrumbs\Breadcrumbs;

// Home
Breadcrumbs::for('home', function ($trail) {
    $trail->push('Главная', route('home'));
});

// Home > transactions
Breadcrumbs::for('transactions', function ($trail) {
    // $trail->parent('home');
    $trail->push('Выбор сделки', route('transactions'));
});
// Home > history
Breadcrumbs::for('history', function ($trail) {
    // $trail->parent('home');
    $trail->push('История', route('history'));
});
