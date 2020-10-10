<?php

namespace Tests\Feature;

use App\Models\Quote;
use App\Models\User;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class QuotesApiTest extends TestCase
{
    use WithFaker;

    const API_PATH = '/api/v1/quote';
    const AUTH_DRIVER = 'api';

    private $sampleUser;
    private $sampleQuote;

    public function setUp(): void
    {
        parent::setUp();

        $this->sampleUser = User::firstOrFail();
        $this->sampleQuote = Quote::firstOrFail();
    }

    public function testFetchSingleQuote(): void
    {
        $this->getJson(self::API_PATH . "/{$this->sampleQuote->id}")->assertOk();
    }

    public function testFetchSingleRandomQuote(): void
    {
        $this->getJson(self::API_PATH . '/random')->assertOk();
        $this->getJson(self::API_PATH . '/random?category=programming')->assertOk();
    }

    public function testFetchAllQuotes(): void
    {
        $this->getJson(self::API_PATH)->assertOk();
        $this->getJson(self::API_PATH . '?category=programming')->assertOk();
    }

    public function testCreateAQuote(): void
    {
        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->postJson(self::API_PATH, [
                'quote' => $this->faker->sentence,
                'author' => $this->faker->name,
                'category' => $this->faker->word
            ])
            ->assertCreated();
    }

    public function testUpdateAQuote(): void
    {
        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->putJson(self::API_PATH . "/{$this->sampleQuote->id}", [
                'quote' => $this->faker->sentence,
                'author' => $this->faker->name,
                'category' => $this->faker->word
            ])
            ->assertOk();

        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->patchJson(self::API_PATH . "/{$this->sampleQuote->id}", [
                'quote' => $this->faker->sentence,
                'author' => $this->faker->name,
                'category' => $this->faker->word
            ])
            ->assertOk();
    }

    public function testDeleteAQuote(): void
    {
        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->deleteJson(self::API_PATH . "/{$this->sampleQuote->id}")
            ->assertOk();
    }

    public function testNotFoundRequests(): void
    {
        $this->getJson(self::API_PATH . '/-1')->assertNotFound();
        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->putJson(self::API_PATH . '/-1')
            ->assertNotFound();
        $this->actingAs($this->sampleUser, self::AUTH_DRIVER)
            ->deleteJson(self::API_PATH . '/-1')
            ->assertNotFound();
    }

    public function testUnauthorizedRequests(): void
    {
        $this->postJson(self::API_PATH)->assertUnauthorized();
        $this->putJson(self::API_PATH . '/1')->assertUnauthorized();
        $this->deleteJson(self::API_PATH . '/1')->assertUnauthorized();
    }
}
