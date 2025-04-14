<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PortfolioController extends Controller
{
    public function index()
    {
        return view('pages.portfolio', [
            'title' => 'Наши работы по строительству в Волгограде | Фото и отзывы',
            'description' => 'Реализованные проекты строительства домов, заборов и навесов в Волгограде. Фото объектов до и после с отзывами клиентов.',
            'keywords' => 'портфолио строительства Волгоград, наши работы, фото домов Волгоград'
        ]);
    }
}
