<?php namespace App\Helpers;

class StringHelper {


    public static function generate_random_code($length = 6) {
        return rand(pow(10,($length-1)), pow(10,$length)-1);
    }
}