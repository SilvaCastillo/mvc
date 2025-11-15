<?php

namespace App\tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LibraryControllerTest extends WebTestCase
{
    public function testBooksRouteWorks(): void
    {
        $client = static::createClient();

        $client->request('GET', '/library/books');

        $this->assertSelectorExists('.group img');
    }
}