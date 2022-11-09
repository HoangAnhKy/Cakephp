<?php

function dayOfProgrammer($year) {
    // Write your code here
    if($year > 1918){
        $month_2 =  ($year % 400 == 0 || $year % 4 == 0 && $year % 100 != 0) ? 29 : 28;
    }
    $month_array = [31,28,31,30,31,30,31,31];
    $month_array[1] = $month_2;
    
    $sum_month = array_sum($month_array);

    return 256 - $sum_month.'.09.'.$year;
}
echo dayOfProgrammer(1800);