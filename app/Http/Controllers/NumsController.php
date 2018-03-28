<?php

namespace App\Http\Controllers;

// use App\Http\Requests;

// use App\Http\Controllers\Controller;

use Illuminate\Http\Request;
class NumsController extends Controller
{
    
    public function getMaxPrimesMultiplicationPalindrome()
    {
        set_time_limit(36000);
        $start = 10000;
        $end = 99999;
        $maxPalindrome = 0;
        $primeNumbers = [];
        for ($n = $start; $n <= $end; $n++) {
            $prime = true;
            for ($i = 2; $i <= sqrt($n); $i++) {
                if (!($n % $i)) {
                    $prime = false;
                    break;
                }
            }
            if ($prime && ($n != 1)) {
                $primeNumbers[] = $n;
            }
        }
        foreach ($primeNumbers as $firstPrime) {
            foreach ($primeNumbers as $secondPrime) {
                $primesMultiplication = $firstPrime*$secondPrime;
                if (($primesMultiplication == strrev($primesMultiplication)) && ($primesMultiplication > $maxPalindrome)) {
                    $maxPalindrome = $primesMultiplication;
                    $a = $firstPrime;
                    $b = $secondPrime;
                }
            }
        }
        echo 'Наибольшее число палиндром, которое является произведением двух простых пятизначных чисел в диапазоне от ' . $start . ' до ' . $end . ': ' . $maxPalindrome . '. Множители: ' . $a . ', ' . $b . '.';
    }
   

    public function getMaxPolindrom()
    {
        $start = 10000;
        $end = 99999;
        $maxPolindrome = 0;
        for ($n = $start; $n <= $end; $n++) {
            $prime = true;
            $polindrome = false;
            $nPolindrome = strrev($n);
            for ($i = 2; $i <= sqrt($n); $i++) {
                if (!($n % $i)) {
                    $prime = false;
                    break;
                }
                if ($n == $nPolindrome) {
                    $polindrome = true;
                }
            }
            if ($prime & $polindrome) {
                if ($maxPolindrome < $n) {
                    $maxPolindrome = $n;
                }
            }
        }
        echo 'Максимальный полиндром простого числа в диапазоне от ' . $start . ' до ' . $end . ': ', $maxPolindrome;
            // pre($maxPolindrome . '|' . $a .'|' .$b);
    }


}