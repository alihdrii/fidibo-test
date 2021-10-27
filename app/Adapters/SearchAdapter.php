<?php

namespace App\Adapters;

use Illuminate\Support\Facades\Http;

class SearchAdapter
{

    private $keyword;

    function __construct($keyword)
    {
        $this->keyword = $keyword;
    }

    public function bookSearch()
    {
        $response = Http::post('https://search.fidibo.com', [
            'q' => $this->keyword,
        ]);
        return $response->json();
    }
}
