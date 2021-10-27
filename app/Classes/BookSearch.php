<?php

namespace App\Classes;

use App\Adapters\SearchAdapter;
use App\Converters\SearchConvert;
use Illuminate\Support\Facades\Redis;
use PhpParser\Node\Stmt\Return_;
use stdClass;

class BookSearch
{

    private $keyword;

    function __construct($keyword)
    {
        $this->keyword = $keyword;
    }

    public function search()
    {
        $adapter = new SearchAdapter($this->keyword);
        $adapter_book_search_result = $adapter->bookSearch();
        if ($this->checkSearchCache()->status) {
            return $this->makeSearchResponse(true, $this->checkSearchCache()->data);
        }
        $search_converter = new SearchConvert($adapter_book_search_result);
        $provider_result = $search_converter->convert();
        Redis::setex($this->keyword, 600, json_encode($provider_result)); // 600 seconds
        return $this->makeSearchResponse(true, $provider_result);
    }

    private function checkSearchCache()
    {
        $cache_result = new stdClass;
        if (Redis::get($this->keyword)) {
            $cache_result->status = true;
            $cache_result->data = json_decode(Redis::get($this->keyword));
            return $cache_result;
        }
        $cache_result->status = false;
        $cache_result->data = 'cache is empty!';
        return $cache_result;
    }

    private function makeSearchResponse($status, $data)
    {
        $result = new stdClass;
        $result->status = $status;
        $result->data = $data;
        return $result;
    }
}
