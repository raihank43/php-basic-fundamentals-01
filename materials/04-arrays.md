# Module 4 — Arrays (indexed + associative)

> Goal: internalize that PHP "arrays" are ordered maps. The same type does the job of JS arrays AND JS objects.

## 1. One data structure, two modes

```php
$indexed = [10, 20, 30];                       // keys 0, 1, 2 (implicit)
$assoc   = ['name' => 'Raihan', 'age' => 25];  // string keys
$mixed   = [0 => 'a', 'name' => 'b', 1 => 'c']; // legal but ugly
```

Internally, ALL PHP arrays are ordered hash maps. Iteration order is insertion order (like modern JS objects, unlike old JS).

**JS analogue:** think `Map` that's always ordered. It's NOT a JS array, and it's NOT a JS object — it's both.

## 2. Reading and writing

```php
$user = ['name' => 'Raihan', 'role' => 'jsdev'];

echo $user['name'];          // Raihan
$user['email'] = 'r@x.com';  // add
$user['role']  = 'phpdev';   // update
unset($user['role']);        // delete
```

Reading a missing key emits a **warning** and returns `null`:

```php
echo $user['nope'];  // Warning: Undefined array key "nope"
```

Safe checks:

```php
isset($user['email'])           // true if key exists AND not null
array_key_exists('email', $user) // true if key exists (even if value is null)
$user['email'] ?? 'default'      // null-coalescing fallback
```

**JS analogue:** reading `obj.missing` in JS just returns `undefined` silently. PHP warns — so always guard with `isset` / `??`.

## 3. Appending to indexed arrays

```php
$items = [];
$items[] = 'a';      // append
$items[] = 'b';      // append
// $items is now ['a', 'b']

array_push($items, 'c', 'd');  // same idea, with a function
```

## 4. Useful inspection functions

```php
count($arr)                   // length
array_keys($assoc)            // ['name', 'age']
array_values($assoc)          // ['Raihan', 25]
in_array('Raihan', $assoc)    // true — searches VALUES (strict mode: in_array('R', $a, true))
array_search('Raihan', $assoc) // returns the KEY, or false if not found
empty($arr)                   // true if empty OR falsy
```

## 5. Nested arrays

```php
$users = [
    ['id' => 1, 'name' => 'Raihan'],
    ['id' => 2, 'name' => 'Joko'],
];

echo $users[0]['name'];  // Raihan

foreach ($users as $u) {
    echo $u['name'], PHP_EOL;
}
```

## 6. Destructuring

```php
$point = ['x' => 10, 'y' => 20];
['x' => $x, 'y' => $y] = $point;  // associative destructuring

[$a, $b, $c] = [1, 2, 3];         // indexed destructuring
```

**JS analogue:** same mechanic as JS destructuring, different syntax shape.

## 7. Strict vs loose comparison on arrays

```php
['a' => 1, 'b' => 2] == ['b' => 2, 'a' => 1];   // true — same key/values, order ignored
['a' => 1, 'b' => 2] === ['b' => 2, 'a' => 1];  // false — order matters with ===
```

---

## Exercise

Create `exercises/04-arrays/users.php`.

1. Build an associative-of-associative array `$users` with 5 users. Each user has: `id` (int), `name` (string), `email` (string), `role` (string — either `"admin"` or `"member"`).

2. Loop with `foreach` and print each user's email.

3. Count how many have `role === "admin"` and print `"X admins out of Y users"`.

4. Write a function `addUser(array $users, array $new): array` that returns a new array with `$new` appended ONLY if an entry with the same `id` doesn't already exist. If a duplicate is found, return `$users` unchanged and print a warning.

5. Call `addUser` twice — once with a new id, once with an existing id — and print the resulting array each time.

Use `isset` or `array_key_exists` appropriately. No loops inside `addUser` for id-check is ideal (hint: build a lookup by id using `array_column`, but a simple `foreach` is also fine for now).

When done, tell me "review module 4".
