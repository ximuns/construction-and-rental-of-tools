<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('pages.portfolio', [
            'title' => 'Портфолио',
            'description' => 'Meta-описание главной страницы',
            'keywords' => 'ИМ, Электронные товары, скидки',
        ]);
    }
}
