<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiTest extends TestCase
{
    use DatabaseMigrations;

    public function testCreateShortUrlSingle()
    {
        $this->post('/api/shorten', [
            'url' => 'http://google.com/123'
        ]);

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                'short_code',
                'short_url',
                'created_at'
            ]
        ]);
    }

    public function testCreateShortUrlSingleWithInvalidUrl()
    {
        $this->post('/api/shorten', [
            'url' => 'http:google.com'
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => [
                'errors' => [
                    'url' => [
                        'The url format is invalid.'
                    ]
                ]
            ]
        ]);
    }

    public function testCreateShortUrlMulti()
    {

        $this->post('/api/shorten', [
            'urls' => [
                'desktop' => 'https://www.reddit.com/',
                'mobile' => 'https://m.reddit.com/',
                'tablet' => 'https://m.reddit.com/'
            ]
        ]);

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                'short_code',
                'short_url',
                'created_at'
            ]
        ]);
    }

    public function testCreateShortUrlMultiWithInvalidUrl()
    {
        $this->post('/api/shorten', [
            'urls' => [
                'desktop' => 'https:/www.reddit.com/',
                'mobile' => 'https:/m.reddit.com/',
                'tablet' => 'https:/m.reddit.com/'
            ]
        ]);

        $this->assertResponseStatus(422);
        $this->seeJson([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => [
                'errors' => [
                    'urls.desktop' => [
                        'The urls.desktop format is invalid.'
                    ],
                    'urls.mobile' => [
                        'The urls.mobile format is invalid.'
                    ],
                    'urls.tablet' => [
                        'The urls.tablet format is invalid.'
                    ],
                ]
            ]
        ]);
    }

    public function testCreateShortUrlWithoutData()
    {
        $this->post('/api/shorten');

        $this->assertResponseStatus(422);
        $this->seeJson([
            'code' => 422,
            'message' => 'Validation failed',
            'data' => [
                'errors' => 'url/urls field missing or empty'
            ]
        ]);
    }

    public function testListUrls()
    {
        $shortUrls = factory(App\Models\ShortUrl::class, 11)->create();

        $shortUrls->each(function ($u) {
            $u->urls()->save(factory(App\Models\DeviceUrl::class)->make());
        });

        $this->get('http://localhost/api/urls');

        $this->assertResponseOk();
        $this->seeJsonStructure([
            'data' => [
                'total',
                'per_page',
                'current_page',
                'last_page',
                'next_page_url',
                'prev_page_url',
                'from',
                'to',
                'data'
            ]
        ]);

        $this->seeJson([
            'total' => 11,
            'per_page' => 10,
            'current_page' => 1,
            'last_page' => 2,
            'from' => 1,
            'to' => 10
        ]);

    }

    public function testListUrlsWithPage()
    {
        $shortUrls = factory(App\Models\ShortUrl::class, 13)->create();

        $shortUrls->each(function ($u) {
            $u->urls()->save(factory(App\Models\DeviceUrl::class)->make());
        });

        $this->get('http://localhost/api/urls?page=2');

        $this->assertResponseOk();
        $this->seeJsonStructure( ['data' => [
            'total', 'per_page', 'current_page', 'last_page',
            'next_page_url', 'prev_page_url', 'from', 'to', 'data'
        ]]);

        $this->seeJson([
            'total' => 13,
            'per_page' => 10,
            'current_page' => 2,
            'last_page' => 2,
            'from' => 11,
            'to' => 13
        ]);

    }

}
