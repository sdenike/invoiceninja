<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\Company;
use Faker\Factory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Tests\TestCase;

class ClientTest extends TestCase
{


    public function setUp()
    {
        parent::setUp();

        Session::start();

        $this->faker = \Faker\Factory::create();

        Model::reguard();

    }

    public function testClientList()
    {

        $data = [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'ALongAndBrilliantPassword123',
            '_token' => csrf_token(),
            'privacy_policy' => 1,
            'terms_of_service' => 1
        ];


        $response = $this->withHeaders([
                'X-API-SECRET' => config('ninja.api_secret'),
            ])->post('/api/v1/signup', $data);


        $response->assertStatus(200)
                ->assertJson([
                'first_name' => $data['first_name'],
            ]);

        $acc = $response->json();

        $account = Account::find($acc['id']);

        $token = $this->account->default_company->tokens()->first()->token;

        $response = $this->withHeaders([
                'X-API-SECRET' => config('ninja.api_secret'),
                'X-API-TOKEN' => $token,
            ])->get('/api/v1/clients');

        $response->assertStatus(200);

    }

    public function testClientShow()
    {
        $account = Account::all()->first();
        $token = $account->default_company->tokens()->first()->token;

        $client = $account->default_company->clients()->first();

        $this->assertEquals(var_dump($account->default_company->clients->first()),1);

        $response = $this->withHeaders([
                'X-API-SECRET' => config('ninja.api_secret'),
                'X-API-TOKEN' => $token,
            ])->get('/api/v1/clients/'.$client->id);

        $response->assertStatus(200);
    }


}
