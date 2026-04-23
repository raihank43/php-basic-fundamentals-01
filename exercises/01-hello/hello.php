<?php

$greetings = "Hello, my name is Raihan";
$todaysDate = date("Y-m-d");
$php_version = PHP_VERSION;



echo $greetings;
print $greetings;

echo $todaysDate;

var_dump($greetings);
var_dump("Today is:", $todaysDate);
var_dump(PHP_VERSION);

echo PHP_EOL;
echo "Answer for exercise 1:", "\n";
echo $greetings, "\n";
echo "Today is ", $todaysDate, ".\n";
echo "PHP Version: ", $php_version;