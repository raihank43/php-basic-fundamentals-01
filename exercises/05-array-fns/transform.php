<?php

$users = [
    [
        "id" => 1,
        "name" => "Jack",
        "email" => "jack@mail.com",
        "role" => "admin",
    ],
    [
        "id" => 2,
        "name" => "Raihan",
        "email" => "raihan@mail.com",
        "role" => "admin",
    ],
    [
        "id" => 3,
        "name" => "Ahmad",
        "email" => "ahmad@mail.com",
        "role" => "member",
    ],
    [
        "id" => 4,
        "name" => "Wei",
        "email" => "wei@mail.com",
        "role" => "member",
    ],
    [
        "id" => 5,
        "name" => "Singh",
        "email" => "Singh@mail.com",
        "role" => "member",
    ],

];

## 1. Use array_map to produce $names — an array of just names.
echo "1. Use array_map to produce \$users — an array of just names", PHP_EOL;
$userNames =  array_map(fn($user) => $user["name"], $users);
print_r($userNames);

## 2. Use array_filter to produce $admins — users whose role is "admin". Reindex the result.
echo "2. Use array_filter to produce \$admins — users whose role is 'admin'. Reindex the result.", PHP_EOL;
$userAdmins = array_values(array_filter($users, fn($user) => $user["role"] === "admin"));
print_r($userAdmins);

## 3. Use array_reduce to produce $totalEmailLen — the sum of the lengths of every user's email string.
echo "3. Use array_reduce to produce \$totalEmailLen — the sum of the lengths of every user's email string.", PHP_EOL;
$totalEmailLength = array_reduce($users, fn($carry, $user) => $carry + strlen($user["email"]), 0);
print_r($totalEmailLength);

echo " ", PHP_EOL;
## Use array_reduce to produce $ byRole — an associative array mapping role → count. Example output: ['admin' => 2, 'member' => 3].
echo "4. Use array_reduce to produce \$byRole — an associative array mapping role → count. Example output: ['admin' => 2, 'member' => 3].", PHP_EOL;
$userByRole = array_reduce($users, function ($carry, $user) {
    $role = $user["role"];

    if (!isset($carry[$role])) {
        $carry[$role] = 0;
    }

    $carry[$role]++;
    return $carry;
}, []);
print_r($userByRole);

echo " ", PHP_EOL;


## Write an arrow function that captures a $minLen variable from outer scope and filters names to only those at least that long.
echo "5. Write an arrow function that captures a \$minLen variable from outer scope and filters names to only those at least that long.", PHP_EOL;
$minLen = 5;
# get all userNames from variable in task 1
$longNames = array_values(array_filter($userNames, fn($name) => strlen($name) >= $minLen));

print_r($longNames);
