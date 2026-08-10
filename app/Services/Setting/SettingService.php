<?php

namespace App\Services\Setting;

interface SettingService
{
    public function getSetting();

    public function updateSetting($data);
}
