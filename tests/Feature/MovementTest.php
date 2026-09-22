<?php

namespace Tests\Feature;

use App\Models\Movement;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Tests\TestCase;

class MovementTest extends TestCase
{
    use RefreshDatabase;

    protected function validPayload(array $overrides = []): array
    {
        return array_merge([
            'value' => 100.50,
            'period' => '01/2026',
            'dateBuy' => '10/01/2026',
            'quantity' => 1,
            'description' => 'Compra teste',
            'memberID' => null,
            'originID' => null,
            'categoryID' => null,
        ], $overrides);
    }

    public function test_creating_movement_with_quantity_links_all_installments_to_same_group(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('data.store'), $this->validPayload(['quantity' => 5]))
            ->assertRedirect(route('panel.data'));

        $movements = Movement::orderBy('id')->get();

        $this->assertCount(5, $movements);
        $this->assertCount(1, $movements->pluck('group_id')->unique());
        $this->assertNotNull($movements->first()->group_id);
        $this->assertSame(
            ['1/5', '2/5', '3/5', '4/5', '5/5'],
            $movements->pluck('installment')->all()
        );
    }

    public function test_creating_a_single_movement_still_receives_a_group_id(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('data.store'), $this->validPayload(['quantity' => 1]))
            ->assertRedirect(route('panel.data'));

        $movement = Movement::sole();

        $this->assertNotNull($movement->group_id);
        $this->assertSame('1/1', $movement->installment);
    }

    public function test_quantity_validation_boundaries(): void
    {
        $rules = ['quantity' => 'required|integer|min:1|max:1000'];

        $this->assertTrue(Validator::make(['quantity' => 1], $rules)->passes());
        $this->assertTrue(Validator::make(['quantity' => 1000], $rules)->passes());
        $this->assertTrue(Validator::make(['quantity' => 0], $rules)->fails());
        $this->assertTrue(Validator::make(['quantity' => 1001], $rules)->fails());
    }

    public function test_creating_one_thousand_movements_persists_all_of_them(): void
    {
        $this->actingAs(User::factory()->create());

        $this->post(route('data.store'), $this->validPayload(['quantity' => 1000]))
            ->assertRedirect(route('panel.data'));

        $this->assertSame(1000, Movement::count());
        $this->assertCount(1, Movement::pluck('group_id')->unique());
    }

    public function test_destroy_installments_only_deletes_the_selected_ids_within_the_same_group(): void
    {
        $this->actingAs(User::factory()->create());

        $groupId = (string) Str::uuid();

        $first = Movement::factory()->create(['group_id' => $groupId, 'installment' => '1/3']);
        $second = Movement::factory()->create(['group_id' => $groupId, 'installment' => '2/3']);
        $third = Movement::factory()->create(['group_id' => $groupId, 'installment' => '3/3']);

        $otherGroupMovement = Movement::factory()->create([
            'group_id' => (string) Str::uuid(),
            'installment' => '1/1',
        ]);

        $this->delete(route('data.destroyInstallments', ['groupID' => $groupId]), [
            'ids' => [$first->id, $third->id, $otherGroupMovement->id],
        ])->assertRedirect(route('panel.data'));

        $this->assertModelMissing($first);
        $this->assertModelMissing($third);

        $second->refresh();
        $this->assertSame('2/3', $second->installment);

        $this->assertModelExists($otherGroupMovement);
    }
}
