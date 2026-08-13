<?php

namespace App\Repositories\StockTransaction;

use Carbon\Carbon;
use App\Models\Category;
use App\Events\ModelActivity;
use App\Models\StockTransaction;
use Illuminate\Support\Facades\Artisan;
use LaravelEasyRepository\Implementations\Eloquent;

class StockTransactionRepositoryImplement extends Eloquent implements StockTransactionRepository {

    /**
    * Model class to be used in this repository for the common methods inside Eloquent
    * Don't remove or change $this->model variable name
    * @property Model|mixed $model;
    */
    protected $model;

    public function __construct(StockTransaction $model)
    {
        $this->model = $model;
    }

    public function all() {
        return $this->model->with(['product', 'user', 'supplier'])->latest()->get();
    }

    public function allPaginate() {
        return $this->all();
    }

    public function allNoPaginate() {
        return $this->model->with(['product', 'user', 'supplier'])->latest()->get();
    }

    public function find($id) {
        return $this->model->findOrFail($id);
    }

    public function create($data) {
        $stock = $this->model->create($data);

        try {
            if (auth()->check()) {
                $productName = $stock->product->name ?? '-';
                event(new ModelActivity(
                    auth()->user(), 
                    'create', 
                    'Stock_Transaction', 
                    $productName, 
                    "Stock Product with Type \"{$stock->type}\" created successfully",
                    $stock->created_at
                ));
            }
        } catch (\Throwable $e) {}

        return $stock;
    }

    public function update($id, $data) {
        $transaction = $this->model->find($id);
        if (!$transaction) {
            return null;
        }

        $originalData = $transaction->toArray();
        $transaction->update($data);
        $updatedData = $transaction->toArray();

        $changes = array_diff_assoc($updatedData, $originalData);

        if(!empty($changes)) {
            try {
                if (auth()->check()) {
                    $productName = $transaction->product->name ?? '-';
                    event(new ModelActivity(
                        auth()->user(), 
                        'update', 
                        'Stock_Transaction', 
                        $productName, 
                        "Stock Product with Type {$transaction->type} updated successfully",
                        $transaction->updated_at
                    ));
                }
            } catch (\Throwable $e) {}
        }

        return $transaction;
    }
    
    public function delete($id) {
        $transaction = $this->model->find($id);
        if (!$transaction) {
            return false;
        }

        try {
            if (auth()->check()) {
                $productName = $transaction->product->name ?? '-';
                event(new ModelActivity(
                    auth()->user(), 
                    'delete', 
                    'Stock_Transaction', 
                    $productName, 
                    "Stock Product with Type \"{$transaction->type}\" deleted successfully",
                    now()
                ));
            }
        } catch (\Throwable $e) {}

        return $transaction->delete();
    }

    public function filterByType($type) {
        $query = $this->model->query();

        if($type) {
            $query->where('type', $type);
        }
        return $query->with(['product', 'user', 'supplier'])->get();
    }

    public function filterByTypeNoPaginate($type) {
        $query = $this->model->query();

        if($type) {
            $query->where('type', $type);
        }
        return $query->with(['product', 'user', 'supplier'])->get();
    }

    public function filterByCriteria($criteria) {
        $query = $this->model->query();

        // By Period Date
        if(!empty($criteria['periods'])) {
            $startDate = null;
            $endDate = null;

            switch($criteria['periods']) {
                case '7 Days':
                    $startDate = now()->subDays(7);
                    $endDate = now();
                    break;
                case '30 Days':
                    $startDate = now()->subDays(30);
                    $endDate = now();
                    break;
                case '3 Month':
                    $startDate = now()->subMonths(3);
                    $endDate = now();
                    break;
                case 'custom':
                    if(!empty($criteria['start_date']) && !empty($criteria['end_date'])) {
                        $startDate = Carbon::parse($criteria['start_date'])->startOfDay();
                        $endDate = Carbon::parse($criteria['end_date'])->endOfDay();
                    }
                    break;
            }
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }

        // By Product Category
        if(!empty($criteria['categories'])) {
            $category = Category::find($criteria['categories']);
            if($category) {
                $query->whereHas('product', function($query) use ($criteria) {
                    $query->where('category_id', $criteria['categories']);
                });
            }
        }

        return $query->with(['product', 'user', 'supplier'])->get();
    }

    public function filterByCriteriaNoPaginate($criteria) {
        $query = $this->model->query();

        // By Period Date
        if(!empty($criteria['periods'])) {
            $startDate = null;
            $endDate = null;

            switch($criteria['periods']) {
                case '7 Days':
                    $startDate = now()->subDays(7);
                    $endDate = now();
                    break;
                case '30 Days':
                    $startDate = now()->subDays(30);
                    $endDate = now();
                    break;
                case '3 Month':
                    $startDate = now()->subMonths(3);
                    $endDate = now();
                    break;
                case 'custom':
                    if(!empty($criteria['start_date']) && !empty($criteria['end_date'])) {
                        $startDate = \Carbon\Carbon::parse($criteria['start_date'])->startOfDay();
                        $endDate = \Carbon\Carbon::parse($criteria['end_date'])->endOfDay();
                    }
                    break;
            }
            
            if ($startDate && $endDate) {
                $query->whereBetween('date', [$startDate, $endDate]);
            }
        }

        // By Product Category
        if(!empty($criteria['categories'])) {
            $category = \App\Models\Category::find($criteria['categories']);
            if($category) {
                $query->whereHas('product', function($query) use ($criteria) {
                    $query->where('category_id', $criteria['categories']);
                });
            }
        }

        return $query->with(['product', 'user', 'supplier'])->get();
    }

    public function getMinimumStock() {
        return config('stock.minimum_stock', 0);
    }

    public function updateMinimumStock($minQuantity) {
        $path = config_path('stock.php');
        if (file_exists($path)) {
            $content = file_get_contents($path);

            $replaceContent = preg_replace(
                "/'minimum_stock' => (\d+)/",
                "'minimum_stock' => {$minQuantity}",
                $content
            );

            file_put_contents($path, $replaceContent);

            Artisan::call('config:clear');
            Artisan::call('config:cache');
        }
    }

    public function countTransactionByTypeAndPeriod($type, $days = 30) {
        $startDate = now()->subDays($days)->startOfDay();
        $endDate = now()->endOfDay();

        return $this->model
            ->where('type', $type)
            ->whereBetween('created_at', [$startDate, $endDate])
            ->count();
    }

    public function transactionByMonthAndYear($type) {
        $record = $this->model->selectRaw('MONTH(date) as month, YEAR(date) as year, SUM(quantity) as total_quantity')
            ->where('type', $type)
            ->groupBy('month', 'year')
            ->orderByRaw('year, month')
            ->get();

        return $record;
    }

    public function countLowStock($minQuantity) {
        return $this->model->where('quantity', '<=', $minQuantity)->count();
    }
}
