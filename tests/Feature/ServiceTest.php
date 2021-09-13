<?php

namespace Tests\Feature;

use App\Models\WebService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Mockery\MockInterface;
use Tests\TestCase;

class ServiceTest extends TestCase
{
    use RefreshDatabase;

    public function setup(): void
    {
        parent::setup();
        $this->user = $this->authUser();
    }

    public function test_a_user_can_connect_to_a_service_and_token_is_stored()
    {

        $response = $this->getJson(route('web-service.connect', 'google-drive'))
            ->assertOk()
            ->json();

        $this->assertNotNull($response['url']);
    }

    public function test_service_callback_will_store_token()
    {
        $mock = $this->mock(WebService::class, function (MockInterface $mock) {
            $mock->shouldReceive('setClientId');
            $mock->shouldReceive('fetchAccessTokenWithAuthCode');
        });

        $res = $this->postJson(route('web-service.callback'), [
            'code' => 'dummyCode'
        ])
            ->assertCreated();
        // access_token, id and secret
        // token field, as a json
        $this->assertDatabaseHas('web_services', ['user_id' => $this->user->id]);
        $this->assertNotNull($this->user->services->first()->token);
    }
}
