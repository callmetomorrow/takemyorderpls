<?php

use Illuminate\Support\Facades\Route;
const PHONE_CODE_UA = '380';
const PHONE_CODE_UA_PLUS = '+380';
const PHONE_FORMAT_UNIFIED_MASK = "/^\+380[1-9]{1}\d{8}$/";


if(!function_exists('phoneFormatUnified')) {
    function phoneFormatUnified($input):?string {
        //$mask = sprintf(PHONE_FORMAT_UNIFIED_MASK, PHONE_CODE_UA); 
        
        $input_length = strlen($input);
        if($input_length < 9) return NULL;
        
        switch($input_length) {
            case 13:
                $input = (preg_match(PHONE_FORMAT_UNIFIED_MASK, $input)) ? substr($input, 4) : NULL;
                break;
            case 12:
                $input = (preg_match("/^380[1-9]{1}\d{8}$/", $input)) ? substr($input, 3) : NULL;
                break;
            case 11:
                $input = (preg_match("/^80[1-9]{1}\d{8}$/", $input)) ? substr($input, 2) : NULL;
                break;
            case 10:
                $input = (preg_match("/^0[1-9]{1}\d{8}$/", $input)) ? substr($input, 1) : NULL;
                break;
            case 9:
                $input = (preg_match("/^[1-9]{1}\d{8}$/", $input)) ? $input : NULL;
                break;
            default:
                $input = NULL;
                break;
        }
        
        //dd($mask);
        
        return $input;
    }
}

if(!function_exists('phoneCode')) {
    function phoneCode($wrap = null, $phonecode = '+380'):string {
        return (!empty($wrap)) ? "<a href=\"tel:$phonecode$wrap\">" . $phonecode . $wrap . '</a>'  : $phonecode;
    }
}

if(!function_exists('active_link')) {
    function active_link(string $route_name, string $active = 'active'): string 
    {
        return Route::is($route_name) ? $active : '';
    }
}