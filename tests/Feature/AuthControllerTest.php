<?php

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Passport\Passport;

uses(DatabaseTransactions::class);

describe('AuthController Registration', function () {
    it('can register a new user successfully', function () {
        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => [
                        'id',
                        'name',
                        'email',
                        'email_verified_at',
                        'roles',
                        'permissions',
                        'created_at',
                        'updated_at',
                    ],
                    'token'
                ]
            ])
            ->assertJson([
                'message' => 'User registered successfully',
            ]);

        $this->assertDatabaseHas('users', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
        ]);

        $user = User::where('email', 'john@example.com')->first();
        expect($user->hasRole('viewer'))->toBeTrue();
    });

    it('validates required fields during registration', function () {
        $response = $this->postJson('/api/auth/register', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    });

    it('validates email format during registration', function () {
        $userData = [
            'name' => 'John Doe',
            'email' => 'invalid-email',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });

    it('prevents duplicate email registration', function () {
        User::factory()->create(['email' => 'john@example.com']);

        $userData = [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->postJson('/api/auth/register', $userData);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });
});

describe('AuthController Login', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ])->assignRole('viewer');
    });

    it('can login with valid credentials', function () {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'data' => [
                    'user' => [
                        'id', 'name', 'email', 'email_verified_at',
                        'roles', 'permissions', 'created_at', 'updated_at',
                    ],
                    'token',
                ]
            ])
            ->assertJson(['message' => 'Login successful']);

        expect($response->json('data.token'))->not->toBeEmpty();
    });

    it('rejects login with invalid email', function () {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'wrong@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    });

    it('rejects login with invalid password', function () {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'john@example.com',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson(['message' => 'Invalid credentials']);
    });

    it('validates required fields during login', function () {
        $response = $this->postJson('/api/auth/login', []);
        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email', 'password']);
    });

    it('validates email format during login', function () {
        $response = $this->postJson('/api/auth/login', [
            'email' => 'invalid-email',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    });
});

describe('AuthController Logout', function () {
    beforeEach(function () {
        $this->user = User::factory()->create([
            'email' => 'john@example.com',
            'password' => Hash::make('password123'),
        ])->assignRole('viewer');
    });

    it('can logout successfully with valid token', function () {
        Passport::actingAs($this->user);
        $response = $this->postJson('/api/auth/logout');

        $response->assertStatus(200)
            ->assertJson(['message' => 'Successfully logged out']);
    });
});

