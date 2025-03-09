<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Support\Facades\Hash;

abstract class TestCase extends BaseTestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();
    }

    protected function setupAuthenticatedUser()
    {
        $user = User::firstOrCreate(
            ['email' => 'demo@cushon.com'],
            [
                'name' => 'Demo User',
                'password' => Hash::make('password'),
            ]
        );

        $this->actingAs($user);

        return $user;
    }
}
