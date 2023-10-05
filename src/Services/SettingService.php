<?php

namespace Dvhoangfh\Aepay\Services;

use Illuminate\Support\Facades\DB;

class SettingService
{
    const KEY_CACHE = 'setting_app';
    
    public function get($key, $default = '')
    {
        $dataDb = DB::table('settings')->pluck('data');
        $data = json_decode($dataDb[0], true);
        if (isset($data[$key])) {
            return $data[$key];
        } elseif ($default) {
            return $default;
        }
    }
    
}
