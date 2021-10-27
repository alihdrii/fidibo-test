<?php

namespace App\Converters;

use Illuminate\Support\Facades\Http;

class SearchConvert
{

    private $data;

    function __construct($data)
    {
        $this->data = $data;
    }

    public function convert()
    {
        $result = [];
        $books = $this->data['books']['hits']['hits'];
        foreach ($books as $book) {
            $each_book = [];
            $each_book['image_name'] = $book['_source']['image_name'];
            $each_book['id'] = $book['_source']['id'];
            $each_book['title'] = $book['_source']['title'];
            $each_book['content'] = $book['_source']['content'];
            $each_book['slug'] = $book['_source']['slug'];
            $each_book['publishers'] = $book['_source']['publishers'];
            $each_book['authors'] = $book['_source']['authors'];
            array_push($result, $each_book);
        }
        return $result;
    }
}
