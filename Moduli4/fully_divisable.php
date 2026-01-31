<?php

function fullyDivisable($n){

    if(($n % 2 == 0)){
        return "$n is fully divisable by 2";
    }
    else{

        return "$n is not fully divisable by 2";
    }
}
   
print_r(fullyDivisable(10). "<br>");
print_r(fullyDivisable(14). "<br>");
print_r(fullyDivisable(9). "<br>");
print_r(fullyDivisable(3). "<br>");

?>