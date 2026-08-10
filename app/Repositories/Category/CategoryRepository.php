<?php

namespace App\Repositories\Category;

use LaravelEasyRepository\Repository;

interface CategoryRepository extends Repository {

    public function all();

    public function pagination();

    public function find($id);

    public function create($data);

    public function update($id, array $data); 
    
    public function delete($id);
}