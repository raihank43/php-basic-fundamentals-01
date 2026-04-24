<?php

for ($i = 1; $i <= 30; $i++) {
    $fizzBuzz = match (true) {
        $i % 3 === 0 &&  $i % 5 === 0 => "FizzBuzz",
        $i % 3 === 0 => "Fizz",
        $i % 5 === 0 => "Buzz",
        default => $i
    };

    echo $fizzBuzz, PHP_EOL;
}

// Refactored Version
echo "Refactored Version: ", PHP_EOL;
foreach (range(1, 30) as $i) {
    $fizzBuzz = match (true) {
        $i % 3 === 0 &&  $i % 5 === 0 => "FizzBuzz",
        $i % 3 === 0 => "Fizz",
        $i % 5 === 0 => "Buzz",
        default => $i
    };

    echo $fizzBuzz, PHP_EOL;
}
