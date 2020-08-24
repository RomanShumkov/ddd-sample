<?php

namespace Tests;

use Illuminate\Foundation\Testing\Concerns\MakesHttpRequests;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication,
        MakesHttpRequests;

    public function jsonApi($method, $uri, array $data = [], array $headers = [])
    {
        $headers = array_merge([
            'CONTENT_TYPE' => 'application/vnd.api+json',
            'Accept' => 'application/vnd.api+json',
        ], $headers);

        return $this->json($method, $uri, $data, $headers);
    }

    public function postJsonApi($uri, array $data = [], array $headers = [])
    {
        return $this->jsonApi('POST', $uri, $data, $headers);
    }
}
