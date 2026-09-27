<?php

namespace App\Http\Controllers;

use App\Jev\Pages;
use Illuminate\Http\Response;

class SiteController extends Controller
{
    public function sitemap(): Response
    {
        return response(Pages::sitemap(), 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }

    public function llms(): Response
    {
        return response(Pages::llms(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }

    public function robots(): Response
    {
        return response(Pages::robots(), 200, [
            'Content-Type' => 'text/plain; charset=UTF-8',
        ]);
    }
}
