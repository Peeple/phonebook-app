<?php

namespace Tests\Http\Controllers\Api\Contact;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $user = [
        'name' => 'john doe',
        'email' => 'john@doe.com',
        'phone' => '8-800-555-35-35',
        'password' => '123456',
        'confirm_password' => '123456'
    ];

    private array $contact = [
        'name' => 'john doe',
        'phone' => '8 800 555 35 35'
    ];

    public function testAddContact()
    {
        $token = $this->getUserLoginToken();
        $this
            ->withToken($token)
            ->postJson(route('api.contacts.store'), $this->contact, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED);
        return $token;
    }

    public function testAddDuplicateContact()
    {
        $token = $this->getUserLoginToken();
        $this
            ->withToken($token)
            ->postJson(route('api.contacts.store'), $this->contact, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_CREATED);
        $this
            ->withToken($token)
            ->postJson(route('api.contacts.store'), $this->contact, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
    }

    public function testUpdateContact()
    {
        $token = $this->testAddContact();
        $contact = $this->contact;
        $contact['phone'] = '8 800 555 35 36';
        $content = $this->withToken($token)
            ->getJson(route('api.contacts.index'), ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJson(fn(AssertableJson $json) => $json
                ->first(fn(AssertableJson $subJson) => $subJson
                    ->whereContains('name', $contact['name'])
                    ->etc()
                )
                ->etc()
            );
        $responseArray = $content->json();
        $contact['id'] = $responseArray[0]['id'];

        $this->withToken($token)
            ->putJson(route('api.contacts.update', $contact['id']), $contact, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_ACCEPTED);

        return $contact['id'];
    }

    public function testMarkContactAsFavourite()
    {
        $token = $this->testAddContact();

        $contacts = $this->withToken($token)
            ->getJson(route('api.contacts.index'), ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_OK)
            ->assertJsonCount(1)
            ->json();

        $contact = $contacts[0];

        $this->withToken($token)
            ->putJson(route('api.contacts.favourite', $contact['id']), [], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_ACCEPTED);
    }

    public function testDeleteContact()
    {
        $token = $this->getUserLoginToken();
        $contactId = $this->testUpdateContact();

        $this->withToken($token)
            ->deleteJson(route('api.contacts.destroy', $contactId), [], ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_NO_CONTENT);
    }

    private function getUserLoginToken(): string
    {
        $this->postJson(route('api.user.create'), $this->user, ['Accept' => 'application/json']);
        return $this
            ->postJson(route('api.user.login'), $this->user, ['Accept' => 'application/json'])
            ->json('token');
    }
}
