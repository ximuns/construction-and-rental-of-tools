<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class RentController extends Controller
{
    public function index()
    {
        return view('pages.rent', [
            'title' => 'Аренда строительных инструментов в Волгограде',
            'description' => 'Прокат профессиональных инструментов в Волгограде: бетономешалки, перфораторы, дрели, строительные леса. Доставка по городу.',
            'keywords' => 'аренда инструментов Волгоград, прокат строительного оборудования, аренда бетономешалки Волгоград'
        ]);
    }
}
