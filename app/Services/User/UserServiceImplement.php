<?php

namespace App\Services\User;

use Barryvdh\DomPDF\Facade\Pdf;
use LaravelEasyRepository\Service;
use App\Repositories\User\UserRepository;

class UserServiceImplement extends Service implements UserService{

     /**
     * don't change $this->mainRepository variable name
     * because used in extends service class
     */
     protected $mainRepository;

    public function __construct(UserRepository $mainRepository)
    {
      $this->mainRepository = $mainRepository;
    }

    public function getAllUser() {
      return $this->mainRepository->all();
    }

    public function getUserById($id) {
      return $this->mainRepository->find($id);
    }

    public function createUser($data) {
      return $this->mainRepository->create($data);
    }

    public function updateUser($id, $data) {
      return $this->mainRepository->update($id, $data);
    }

    public function deleteUser($id) {
      return $this->mainRepository->delete($id);
    }

    public function getAllUserActivities() {
      return $this->mainRepository->userActivities();
    }

    public function generateActivityReport() {
      $activities = $this->mainRepository->userActivities();

      return Pdf::loadView('reports.userActivitiesReport', [
        'data' => $activities,
        'title' => 'Laporan User Activity',
      ])->stream("user-activities-report.pdf");
    }
}
