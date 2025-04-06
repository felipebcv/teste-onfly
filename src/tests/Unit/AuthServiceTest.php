<?php

namespace Tests\Unit;

use App\Models\User;
use App\Services\AuthService;
use App\Interfaces\Repositories\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    public function test_login_returns_token_when_credentials_are_valid()
    {
        $userRepositoryMock = Mockery::mock(UserRepositoryInterface::class);
        
        $user = User::factory()->make([
            'email' => 'test@example.com',
            'password' => Hash::make('secret'),
        ]);

        $user = Mockery::mock($user)->makePartial();

        $userRepositoryMock->shouldReceive('findByEmail')
            ->once()
            ->with('test@example.com')
            ->andReturn($user);

        $user->shouldReceive('createToken')
             ->once()
             ->with('API Token')
             ->andReturn((object)['plainTextToken' => 'fake-token']);

        $authService = new AuthService($userRepositoryMock);
        $token = $authService->login('test@example.com', 'secret');

        $this->assertEquals('fake-token', $token);
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}
