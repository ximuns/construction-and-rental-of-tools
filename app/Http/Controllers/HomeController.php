<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        return view('pages.index', [
            'title' => 'Строительство домов под ключ в Волгограде',
            'description' => 'Профессиональное строительство домов под ключ в Волгограде. Возведение малоэтажных домов, заборов, навесов, монтаж окон. Бесплатный расчет стоимости.',
            'keywords' => 'строительство под ключ Волгоград, строительство домов Волгоград, заборы Волгоград, навесы Волгоград, монтаж окон Волгоград, аренда инструментов Волгоград',
        ]);
    }
}
