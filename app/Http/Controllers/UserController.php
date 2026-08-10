<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\User\UserService;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index() {
        $user = $this->userService->getAllUser();
        return view('roles.Admin.Users.index', [
            'title' => 'User Management',
            'users' => $user,
        ]);
    }

    private function userValidation($id = null) {
        return [
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => $id ? 'nullable|string|min:8' : 'required|string|min:8',
            'role' => 'required|in:Admin,Staff Gudang,Manajer Gudang',
        ];
    }

    public function store(Request $request) {
        $data = $request->validate($this->userValidation());
        $validatedData['password'] = Hash::make($data['password']);

        $this->userService->createUser($data);

        return redirect()->route('users.index')->with('success', 'User berhasil dibuat.');
    }

    public function show($id) {
        $user = $this->userService->getUserById($id);
        return view('roles.Admin.Users.index', [
            'title' => 'User Detail',
            'user' => $user,
        ]);
    }

    public function edit($id) {
        $user = $this->userService->getUserById($id);
        return view('roles.Admin.Users.edit', [
            'title' => 'User Edit',
            'user' => $user,
        ]);
    }

    public function update(Request $request, $id) {
        $validatedData = $request->validate($this->userValidation($id));

        if (!empty($validatedData['password'])) {
            $validatedData['password'] = Hash::make($validatedData['password']);
        } else {
            unset($validatedData['password']);
        }

        $user = $this->userService->getUserById($id);
        $user->role = $validatedData['role'];

        $this->userService->updateUser($id, $validatedData);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function destroy($id) {
        $this->userService->deleteUser($id);

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
