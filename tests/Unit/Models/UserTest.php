<?php

namespace Tests\Unit\Models;

use App\Models\Station;
use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends \Tests\TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_saving_users()
    {
        $station = User::factory()->create();

        $this->assertDatabaseHas('users', [
            'id' => $station->id,
        ]);
    }
}
