<?php

namespace App\Http\Controllers;

use App\Adapters\SearchAdapter;
use App\Classes\BookSearch;
use App\Converters\SearchConvert;
use App\Http\Requests\SearchingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class SearchingController extends Controller
{

    public function search(SearchingRequest $request)
    {
        $keyword = $request->only(['keyword']);
        $book_search = new BookSearch($keyword['keyword']);
        if (!$book_search->search()->status) {
            return response()->json('some problem!!!', 400);
        }
        return response()->json($book_search->search()->data, 200);
    }
}
