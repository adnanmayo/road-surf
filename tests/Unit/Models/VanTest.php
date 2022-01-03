<?php

namespace Tests\Unit\Models;

use App\Models\Van;

class VanTest extends \Tests\TestCase
{

    public function test_saving_vans()
    {
        $station = Van::factory()->create();

        $this->assertDatabaseHas('vans', [
            'id' => $station->id,
        ]);
    }
}
