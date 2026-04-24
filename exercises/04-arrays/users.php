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

## Print each user's email.
echo "Print Each User's Email:", PHP_EOL;
foreach ($users as $user) {
    echo $user["email"], PHP_EOL;
}

## Count how many have role === admin
echo "Count how many have role === Admin", PHP_EOL;
$adminsCount = 0;
foreach ($users as $user) {
    if ($user["role"] === "admin") {
        $adminsCount++;
    }
}
echo $adminsCount, " admins out of ", count($users), " users", PHP_EOL;


## Write a function addUser.
echo "Write a function addUser", PHP_EOL;
# two different methods, using forEach and column_array
function addUserForEach(array $users, array $new): array
{
    foreach ($users as $existing) {
        if ($existing["id"] === $new["id"]) {
            echo "Warning: user with id {$new['id']} already exists.", PHP_EOL;
            return $users;
        }
    }
    $users[] = $new;
    return $users;
}

function addUserColumnArray(array $users, array $new): array
{
    $existingIds = array_column($users, "id");
    if (in_array($new["id"], $existingIds, true)) {
        echo "Warning: user with id {$new['id']} already exists.", PHP_EOL;
        return $users;
    }
    $users[] = $new;
    return $users;
}

$newUser =    [
    "id" => 6,
    "name" => "Rico",
    "email" => "rico@mail.com",
    "role" => "member",
];

$existingUser =    [
    "id" => 1,
    "name" => "Jack",
    "email" => "jack@mail.com",
    "role" => "Admin",
];

echo ("=====Using forEach function====="), PHP_EOL;

echo "Print add new User:", PHP_EOL;
print_r(addUserForEach($users, $newUser));

echo "Print add existing User:", PHP_EOL;
print_r(addUserForEach($users, $existingUser));


echo ("=====Using column_array function====="), PHP_EOL;

echo "Print add new User:", PHP_EOL;
print_r(addUserColumnArray($users, $newUser));

echo "Print add existing User:", PHP_EOL;
print_r(addUserColumnArray($users, $existingUser));
