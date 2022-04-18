<?php

namespace Tests\Http\Controllers\Api\User;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Symfony\Component\HttpFoundation\Response;
use Tests\TestCase;
use Throwable;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    private array $user = [
        'name' => 'john doe',
        'email' => 'john@doe.com',
        'phone' => '8-800-555-35-35',
        'password' => '123456',
        'confirm_password' => '123456'
    ];

    /**
     * @return void
     * @throws Throwable
     */
    public function testNewUserCreateSuccessful()
    {
        $this->postJson(route('api.user.create'), $this->user, ['Accept' => 'application/json'])
            ->assertStatus(201)
            ->decodeResponseJson();
    }

    /**
     * @return void
     * @throws Throwable
     *
     * @depends testNewUserCreateSuccessful
     */
    public function testNewUserCreateWithoutParam()
    {
        $user = $this->user;
        unset($user['name']);
        $this->postJson(route('api.user.create'), $user, ['Accept' => 'application/json'])
            ->assertStatus(422)
            ->decodeResponseJson();
    }

    /**
     * @return mixed
     *
     * @throws Throwable
     * @depends testNewUserCreateSuccessful
     */
    public function testUserLogin(): mixed
    {
        $this->testNewUserCreateSuccessful();//Костыль, чтобы пересоздать пользователя и воспользоваться его данными

        $content = $this->postJson(route('api.user.login'), $this->user, ['Accept' => 'application/json']);

        $content->assertJson(fn(AssertableJson $json) => $json->has('token')
            ->etc()
        );

        return $content->json('token');
    }

    /**
     * @return void
     * @throws Throwable
     */
    public function testNewUserUpdateDetails()
    {
        $token = $this->testUserLogin();

        $this->putJson(route('api.user.update'), ['name' => 'john deo'],
            [
                'Accept' => 'application/json',
                'Authentication' => 'Bearer:' . $token
            ])
            ->assertStatus(202)
            ->decodeResponseJson();
    }

    public function testUserForgotPassword()
    {
        $this->testNewUserCreateSuccessful();

        $this->postJson(route('api.user.forgot'), $this->user, ['Accept' => 'application/json'])
            ->assertStatus(Response::HTTP_ACCEPTED);
    }
}
