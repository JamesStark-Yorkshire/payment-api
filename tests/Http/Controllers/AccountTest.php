<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AccountControllerTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testIndex()
    {
        $this->json('GET', 'account')
            ->seeJsonStructure([
                'current_page',
                'data',
                'first_page_url'
            ])
            ->seeJsonContains([
                'id' => 1
            ])
            ->assertResponseOk();
    }

    public function testStore()
    {
        $this->json('POST', 'account')
            ->seeJsonStructure([
                'id',
                'uuid'
            ])
            ->assertResponseStatus(\Illuminate\Http\Response::HTTP_CREATED);
    }
}
