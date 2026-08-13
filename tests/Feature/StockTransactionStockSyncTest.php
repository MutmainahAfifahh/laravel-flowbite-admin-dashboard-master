<?php

namespace Tests\Feature;

use App\Http\Controllers\StockTransactionController;
use App\Models\Product;
use App\Models\StockTransaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class StockTransactionStockSyncTest extends TestCase
{
    use RefreshDatabase;

    public function test_opname_keeps_quantity_locked_to_confirmation_data(): void
    {
        $user = User::factory()->create(['role' => 'Admin']);
        $this->actingAs($user);

        $product = Product::factory()->create();
        $transaction = StockTransaction::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'type' => 'Masuk',
            'quantity' => 7,
            'date' => now()->toDateString(),
            'status' => 'Pending',
            'notes' => 'awal',
        ]);

        $controller = app(StockTransactionController::class);
        $request = Request::create('/stock-opname', 'POST', [
            'stock_id' => [$transaction->id],
            'type' => ['Masuk'],
            'status' => ['Diterima'],
            'minimum_stock' => [99],
            'notes' => ['diupdate'],
        ]);

        $response = $controller->opnameData($request);

        $this->assertInstanceOf(\Illuminate\Http\RedirectResponse::class, $response);
        $transaction->refresh();
        $this->assertSame(7, $transaction->quantity);
        $this->assertSame('diupdate', $transaction->notes);
        $this->assertSame('Diterima', $transaction->status);
    }
}
