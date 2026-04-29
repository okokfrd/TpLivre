<?php

declare(strict_types=1);

namespace App\Controllers;

use App\Core\Auth;
use App\Core\View;

class HomeController
{
    public function dashboard(): void
    {
        Auth::requireLogin();
        View::render('home/dashboard', ['user' => $_SESSION['user']]);
    }
}
