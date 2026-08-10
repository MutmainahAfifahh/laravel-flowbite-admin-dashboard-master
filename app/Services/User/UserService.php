<?php

namespace App\Services\User;

use LaravelEasyRepository\BaseService;

interface UserService extends BaseService{

    public function getAllUser();
    
    public function getUserById($id);
    
    public function createUser($data);  
    
    public function updateUser($id, $data);
    
    public function deleteUser($id);
    
    public function getAllUserActivities();
    
    public function generateActivityReport();
}
