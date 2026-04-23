<?php

function double(int $n): int
{
    return $n * 2;
}

echo double(8), PHP_EOL;

try {
    //code...
    echo double("5"), PHP_EOL;
} catch (TypeError $e) {
    echo "Caught: ", $e->getMessage(), PHP_EOL;
}


try {
    //code...
    echo double(3.14), PHP_EOL;
} catch (TypeError $e) {
    echo "Caught: ", $e->getMessage(), PHP_EOL;
}