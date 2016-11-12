<?php

/*
 * All helpers go here in this file
 */


if(!function_exists('generate_hash')) {
    function random_hash()
    {
        $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';

        $randomLength = mt_rand(3, 8);

        return substr(str_shuffle(str_repeat($chars, 10)), 0, $randomLength);

    }
}