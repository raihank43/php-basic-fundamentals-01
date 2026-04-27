<?php

declare(strict_types=1);
function slugify(string $title): string
{
    $lowerCaseTitle = strtolower($title);
    $replaceNonAlphaNumeric = str_replace([",", "!", "&", ".", ":", ";"], " ", $lowerCaseTitle);
    $explodeTitle = array_values(array_filter(explode(" ", $replaceNonAlphaNumeric), fn($str) => $str !== ""));
    $implodeTitle = implode("-", $explodeTitle);
    $trim = trim($implodeTitle, "-");

    // print_r($explodeTitle);

    return $trim;
};


$str_test_1 = "Hello, World!";
$str_test_2 = " Multiple Spaces ";
$str_test_3 = "PHP & JS: Type Juggling";

echo slugify($str_test_1), PHP_EOL;
echo slugify($str_test_2), PHP_EOL;
echo slugify($str_test_3), PHP_EOL;
