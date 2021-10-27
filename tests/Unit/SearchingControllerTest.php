<?php

namespace Tests\Unit;

// use PHPUnit\Framework\TestCase;
use Illuminate\Http\Response;
use Tests\TestCase;

class SearchingControllerTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function testSearechEndpoint()
    {
        $key = 'داستان';
        $this->json('post', "api/search/book?keyword=$key")
            ->assertStatus(200)
            ->assertJsonStructure([
                '*' => [
                    'image_name',
                    'id',
                    'title',
                    'content',
                    'slug',
                    'publishers' => [
                        'title'
                    ],
                    'authors' => [
                        '*' => [
                            'name'
                        ]
                    ]

                ]
            ]);
    }

    public function testSearechEndpointFailed()
    {
        $key = '';
        $this->json('post', "api/search/book?keyword=$key")
            ->assertStatus(422);
    }
}
