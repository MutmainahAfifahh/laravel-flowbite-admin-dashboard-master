<?php

namespace App\Repositories\ProductAttribute;

use LaravelEasyRepository\Repository;

interface ProductAttributeRepository extends Repository{

    public function all();

    public function find($id);

    public function create($data);

    public function update($id, $data);

    public function delete($id);
}
