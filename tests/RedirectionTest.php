<?php

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class RedirectionTest extends TestCase
{
    use DatabaseMigrations;

    public function testRedirection()
    {
        $shortUrl = factory(App\Models\ShortUrl::class)->create();

        $shortUrl->urls()->save(factory(App\Models\DeviceUrl::class)->make());

        $this->get($this->baseUrl . '/' .$shortUrl->short_code);

        $this->assertResponseStatus(301);
    }

    public function testInvalidUrl()
    {
        $this->get($this->baseUrl . '/invalidUrl');

        $this->assertResponseStatus(404);
    }
}
