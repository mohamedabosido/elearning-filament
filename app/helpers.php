<?php

use App\Models\SiteSetting;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Mail\SentMessage;
use Illuminate\Support\Facades\File;


function getClassesList($dir)
{
    return array_map(fn($f) => str_replace([app_path(), '/', '.php'], ['App', '\\', ''], $f->getRealPath()), File::allFiles($dir));
}

if (!function_exists('replace_arabic_indic')) {
    function replace_arabic_indic($text)
    {
        return  str_replace(['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'], ['0', '1', '2', '3', '4', '5', '6', '7', '8', '9'], $text);
    }
}

if (!function_exists('asset_exists')) {
    function asset_exists($url)
    {
        return file_exists(str_replace(url('/'), public_path(), $url));
    }
}

if (!function_exists('order_direction')) {
    function order_direction($key, $default = null)
    {
        return  in_array($key, ['asc', 'desc']) ? $key : $default;
    }
}

if (!function_exists('price_after_discount_percentage')) {
    function price_after_discount_percentage($price, $percent, $floor_decimal = true)
    {
        $price = $price - ($price * $percent / 100);
        return $floor_decimal ? rounded_number($price) : $price;
    }
}

if (!function_exists('rounded_number')) {
    function rounded_number($price)
    {
        return round($price, 2);
    }
}

if (!function_exists('calculate_discount_percentage')) {
    function calculate_discount_percentage($first, $second, $floor_decimal = true)
    {
        if ($second == 0) {
            return 0;
        }

        $percent = ($first - $second) / $first * 100;
        return $floor_decimal ? rounded_number($percent) : $percent;
    }
}

if (!function_exists('carbon')) {
    function carbon($date)
    {
        return Carbon::parse($date);
    }
}

if (!function_exists('diff_for_humans_abs')) {
    function diff_for_humans_abs($from, $to)
    {
        $options = [
            'join' => ', ',
            'parts' => 2,
            'syntax' => CarbonInterface::DIFF_ABSOLUTE,
        ];
        return $from->diffForHumans($to, $options);
    }
}

if (!function_exists('date_formater')) {
    function date_formater($date)
    {
        if ($date != null) {
            if ($date->diffInDays() > 1) {
                return $date->locale(app()->getLocale())->isoFormat('Do MMMM YYYY, H:mm');
            }
            return $date->diffForHumans();
        }
    }
}

if (!function_exists('mask_string')) {
    function mask_string($string, $masking_character = '*', $unmasked_length = 2,)
    {
        return str($string)->mask($masking_character, $unmasked_length)->value();
    }
}

if (!function_exists('get_table')) {
    function get_table($string)
    {
        return str(class_basename($string))->replace('-', '_')->snake()->plural()->value();
    }
}

if (!function_exists('get_data_table')) {
    // to get data table name from model name // ex: App\Models\Order => App\DataTables\OrdersDataTable
    function get_data_table($string)
    {
        return "App\\DataTables\\" .  str($string)->studly()->plural() . "DataTable";
    }
}

if (!function_exists('get_model')) {
    function get_model($string)
    {
        return "App\\Models\\" .  str($string)->studly()->singular();
    }
}

if (!function_exists('site_settings')) {
    function site_settings($key = null)
    {
        if (!cache('settings')) {
            cache(['settings' => SiteSetting::first()]);
        }
        return $key ? cache('settings')->{$key} : cache('settings');
    }
}

if (!function_exists('generate_code')) {
    function generate_code($digits = 5)
    {
        return fake()->randomNumber($digits, true);
    }
}

if (!function_exists('date_hour_ar')) {
    function date_hour_ar($date)
    {
        return $date ? Carbon::parse($date)->locale(app()->getLocale())->isoFormat('dddd, Do MMMM YYYY, H:mm') : null;
    }
}

if (!function_exists('date_ar')) {
    function date_ar($date)
    {
        return $date ? Carbon::parse($date)->locale(app()->getLocale())->isoFormat('dddd, Do MMMM YYYY') : null;
    }
}

if (!function_exists('check_email')) {
    function check_email($mail)
    {
        return $mail instanceof SentMessage ? 'success' : 'failed';;
    }
}

if (!function_exists('localize_array')) {
    function localize_array($attributes)
    {
        $ar = array_map(fn($l) => [$l => array_map(fn($a) => $a . ' ' . __('main.in_' . $l), $attributes)], appLocales());
        $data = [];
        foreach ($ar as $item) {
            $key = array_keys($item)[0];
            $data[$key] = $item[$key];
        }
        return $data;
    }
}

if (!function_exists('isAr')) {
    function isAr()
    {
        return app()->getLocale() == 'ar';
    }
}

if (!function_exists('appLocales')) {
    function appLocales()
    {
        return config('translatable.locales');
    }
}

if (!function_exists('fileIsImg')) {
    function fileIsImg($path)
    {
        return in_array(pathinfo($path, PATHINFO_EXTENSION), ['jpeg', 'png', 'jpg']);
    }
}

if (!function_exists('auth_user')) {
    function auth_user(): ?\App\Models\User
    {
        return auth()->guard('web')->user();
    }
}

if (!function_exists('auth_instructor')) {
    function auth_instructor(): ?\App\Models\Instructor
    {
        return auth()->guard('instructor')->user();
    }
}

if (!function_exists('get_segment')) {
    function get_segment($path, $segment = 1)
    {
        return $path ? explode("/", parse_url($path, PHP_URL_PATH))[$segment] : null;
    }
}

if (!function_exists('number_format')) {
    function number_format($number, $decimals = 2)
    {
        return number_format($number, $decimals, '.', ',');
    }
}

