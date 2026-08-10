<?php

namespace App\Repositories\Supplier;

use LaravelEasyRepository\Repository;

interface SupplierRepository extends Repository{

    public function all();

    public function pagination();

    public function find($id); 
    
    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
