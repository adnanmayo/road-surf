<?php

namespace Tests\Unit\Models;

use App\Models\Equipment;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;


class EquipmentTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_saving_equipments()
    {
        $equiptment = Equipment::factory()->create();

        $this->assertDatabaseHas('equipments', [
            'id' => $equiptment->id,

        ]);
    }
}
