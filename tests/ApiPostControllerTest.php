<?php

namespace App\Tests;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use PHPUnit\Util\Json;

class ApiPostControllerTest extends ApiTestCase
{
    public function testGetQuestion(): void
    {
        $response = static::createClient()->request('GET', '/api/getQuestions');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $response->getStatusCode());
    }

    public function testPostQuestion(): void
    {
        $response = static::createClient()->request('POST', '/api/postQuestion',
            ['json' => [
            'title' => 'testPostQuestion',
            'promoted' => true,
            'status' => 'published'
        ]]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(201, $response->getStatusCode());
    }

    public function testPostWrongQuestion(): void
    {
        $response = static::createClient()->request('POST', '/api/postQuestion',
            ['json' => [
                'title' => 'testPostQuestion',
                'promoted' => true,
                'status' => 'wrong status'
            ]]);

        $this->assertSame(400, $response->getStatusCode());
    }

    public function testUpdateQuestion(): void
    {
        $response = static::createClient()->request('POST', '/api/updateQuestion',
            ['json' => [
                'id' => 9,
                'title' => 'UpdatedQuestion',
                'status' => 'published'
            ]]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(201, $response->getStatusCode());
    }

    public function testGetHistoric(): void
    {
        $response = static::createClient()->request('GET', '/api/historicCSV');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([0 => 'text/csv; charset=UTF-8'], $response->getHeaders()['content-type']);
    }

    public function testGetEntityByJson(): void
    {
        $response = static::createClient()->request('GET', '/api/entityCSV',
            ['json' => [
                'entity_name' => 'HistoricQuestion'
            ]]);

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame([0 => 'text/csv; charset=UTF-8'], $response->getHeaders()['content-type']);

    }

    public function testGetEntityByStringUrl(): void
    {
        $response = static::createClient()->request('GET', '/api/entityCSV/HistoricQuestion');
        $response1 = static::createClient()->request('GET', '/api/entityCSV/HistoricQuestion');
        $response2 = static::createClient()->request('GET', '/api/entityCSV/HistoricQuestion');

        $this->assertResponseIsSuccessful();
        $this->assertSame(200, $response->getStatusCode());
        $this->assertSame(200, $response1->getStatusCode());
        $this->assertSame(200, $response2->getStatusCode());


        $this->assertSame([0 => 'text/csv; charset=UTF-8'], $response->getHeaders()['content-type']);
        $this->assertSame([0 => 'text/csv; charset=UTF-8'], $response1->getHeaders()['content-type']);
        $this->assertSame([0 => 'text/csv; charset=UTF-8'], $response2->getHeaders()['content-type']);

    }
}
