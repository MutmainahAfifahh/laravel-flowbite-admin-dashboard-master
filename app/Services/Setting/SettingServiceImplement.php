<?php

namespace App\Services\Setting;

use LaravelEasyRepository\Service;
use Illuminate\Support\Facades\Storage;

class SettingServiceImplement extends Service implements SettingService {

    protected $pathData;

    public function __construct()
    {
        $this->pathData = 'settings.json';
    }

    public function getSetting() {
        $default = [
            'app_title' => 'Stockify',
            'app_logo'  => 'images/Logo S.png',
        ];

        if (!Storage::exists($this->pathData)) {
            return $default;
        }

        $data = json_decode(Storage::get($this->pathData), true);

        if (!is_array($data)) {
            return $default;
        }

        return array_merge($default, $data);
    }

    public function updateSetting($data) {
        $currentSetting = $this->getSetting();
        $newSetting = array_merge($currentSetting, array_filter($data));

        Storage::put($this->pathData, json_encode($newSetting, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
    }
}