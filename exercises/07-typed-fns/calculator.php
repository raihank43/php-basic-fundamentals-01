<?php

declare(strict_types=1);

function add(float $a, float $b): float
{
    return $a + $b;
}

function subtract(float $a, float $b): float
{
    return $a - $b;
}

function multiply(float $a, float $b): float
{
    return $a * $b;
}

function divide(float $a, float $b): float
{
    if ($b === 0.0) {
        throw new DivisionByZeroError("Cannot divide $a by zero");
    }
    return $a / $b;
}


function calculate(string $op, float $a, float $b): float
{
    $result = match ($op) {
        "+" => add($a, $b),
        "-" => subtract($a, $b),
        "*" => multiply($a, $b),
        "/" => divide($a, $b),
        default => throw new InvalidArgumentException("Unknown operator: $op"),
    };

    return $result;
}

## DEMO
echo "add", PHP_EOL;
echo calculate("+", 3, 3), PHP_EOL;

echo "subtract", PHP_EOL;
echo calculate("-", 3, 5), PHP_EOL;

echo "multiply", PHP_EOL;
echo calculate("*", 5, 5), PHP_EOL;

echo "divide 1", PHP_EOL;
try {
    echo calculate("/", 5, 0), PHP_EOL;
} catch (\Throwable $th) {
    echo "Oops: ", $th->getMessage(), PHP_EOL;
}

echo "divide 2", PHP_EOL;
try {
    echo calculate("/", 5, 5), PHP_EOL;
} catch (\Throwable $th) {
    echo "Oops: ", $th->getMessage(), PHP_EOL;
}

echo "unknown operator", PHP_EOL;
try {
    echo calculate("mod", 1, 2), PHP_EOL;
} catch (\Throwable $th) {
    echo "Oops: ", $th->getMessage(), PHP_EOL;
}

echo "calling without strict types", PHP_EOL;
try {
    echo add(1, "2"), PHP_EOL;
} catch (\TypeError $e) {
    echo "TypeError: ", $e->getMessage(), PHP_EOL;
}
// it return 3 because it will coerce "2" to float (depending on the type of the params) 2 -> 1 + 2 = 3.

echo "add a variadic sumAll(float ...\$nums): float using array_reduce", PHP_EOL;
function sumAll(float ...$nums): float
{
    return array_reduce($nums, fn($carry, $n) => $carry + $n, 0.0);
}
echo sumAll(1.5, 2.5, 3.0), PHP_EOL;  // 