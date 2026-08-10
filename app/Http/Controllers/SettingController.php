<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Setting\SettingService;

class SettingController extends Controller
{
    protected $settingService;

    public function __construct(SettingService $settingService) 
    {
        $this->settingService = $settingService;
    }

    public function index() 
    {
        // Mengambil data setting via Service
        $settings = $this->settingService->getSetting();

        return view('roles.Admin.Setting.index', [
            'title' => 'Application Settings',
            'setting' => $settings,
            'isDashboardActive' => false, // Variabel dikirim agar view tidak error
        ]);
    }

    public function update(Request $request) 
    {
        $request->validate([
            'app_title' => 'required|string|max:255',
            'app_logo'  => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = ['app_title' => $request->app_title];

        if ($request->hasFile('app_logo')) {
            $file = $request->file('app_logo');
            $filename = 'logo_' . time() . '.' . $file->getClientOriginalExtension();

            // Pindahkan file langsung ke folder public/storage/images/logo/
            $file->move(public_path('storage/images/logo'), $filename);

            // Simpan path publik yang bersih di database
            $data['app_logo'] = 'storage/images/logo/' . $filename;
        }

        $this->settingService->updateSetting($data);

        return redirect()->route('setting.index')->with('success', 'Settings updated successfully');
    }
}