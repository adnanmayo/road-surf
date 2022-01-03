<?php

namespace Tests\Unit\Models;

use App\Models\Station;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\TestCase;

class StationTest extends \Tests\TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_saving_stations()
    {
        $station = Station::factory()->create();

        $this->assertDatabaseHas('stations', [
            'id' => $station->id,
        ]);
    }
}
