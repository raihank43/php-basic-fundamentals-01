# Module 3 — Control Flow

> Goal: know every way PHP branches and loops, and when to reach for which.

## 1. `if / elseif / else`

```php
if ($score >= 90) {
    echo "A";
} elseif ($score >= 80) {   // note: one word "elseif"
    echo "B";
} else {
    echo "F";
}
```

PHP also supports "alternative syntax" with colons (common in templates — skip for CLI work):

```php
if ($x): echo "yes"; else: echo "no"; endif;
```

## 2. Ternary and null-coalescing

```php
$label = $isAdmin ? "admin" : "user";

// Short ternary — returns $a if truthy, else $b
$name = $user ?: "guest";

// Null coalescing — returns $a if set AND not null, else $b
$name = $user ?? "guest";

// Null coalescing assignment
$config['timeout'] ??= 30;
```

**JS analogue:** `??` is identical to JS's `??`. Short ternary `?:` is like `a || b` in JS.

## 3. `match` (PHP 8) — prefer over `switch`

`match` is an **expression** that uses **strict comparison** (`===`):

```php
$label = match ($status) {
    1, 2    => "active",        // multiple matches separated by comma
    3       => "pending",
    default => "unknown",
};

// match(true) pattern — great for range checks
$grade = match (true) {
    $score >= 90 => "A",
    $score >= 80 => "B",
    $score >= 70 => "C",
    default      => "F",
};
```

**Why `match` over `switch`:**
- `match` uses `===`, `switch` uses `==` (type juggling!)
- `match` is an expression — returns a value you can assign
- No fall-through, no `break` needed
- Throws `UnhandledMatchError` if nothing matches and no `default`

**JS analogue:** `match` is closer to a switch expression in Rust/Kotlin. There's no direct JS equivalent.

## 4. Loops

### `for`
```php
for ($i = 0; $i < 10; $i++) {
    echo $i, PHP_EOL;
}
```

### `while` and `do-while`
```php
while ($n > 0) {
    $n--;
}

do {
    $input = getInput();
} while ($input !== "quit");
```

### `foreach` — the one you'll use most

```php
// Indexed array — values only
foreach ($items as $item) {
    echo $item, PHP_EOL;
}

// Associative array — key + value
foreach ($user as $key => $value) {
    echo "$key: $value", PHP_EOL;
}

// By reference — modify in place (rare, but possible)
foreach ($numbers as &$n) {
    $n *= 2;
}
unset($n);  // ALWAYS unset after foreach-by-ref — gotcha!
```

**JS analogue:** `foreach ($arr as $v)` ≈ `for (const v of arr)`. `foreach ($obj as $k => $v)` ≈ `for (const [k, v] of Object.entries(obj))`.

## 5. `break` and `continue`

PHP allows level numbers — `break 2` breaks out of two nested loops:

```php
foreach ($matrix as $row) {
    foreach ($row as $cell) {
        if ($cell === 'STOP') {
            break 2;   // exits BOTH loops
        }
    }
}
```

## 6. `switch` (still legal, but don't reach for it)

Included for completeness. Uses `==`, falls through without `break`, verbose. Prefer `match` in new code.

```php
switch ($day) {
    case "sat":
    case "sun":
        echo "weekend";
        break;
    default:
        echo "weekday";
}
```

---

## Exercise

Create `exercises/03-control-flow/fizzbuzz.php`.

Write classic FizzBuzz 1–30:
- Multiples of 3 → `"Fizz"`
- Multiples of 5 → `"Buzz"`
- Multiples of both → `"FizzBuzz"`
- Otherwise → the number itself

Constraints:
1. Use a `for` loop.
2. Use `match(true)` for the branching — NOT `if/else`.
3. Each line is printed with `echo` + `PHP_EOL`.

**Bonus:** refactor so the range (1–30) is pulled from a `foreach` over `range(1, 30)` instead of a `for` loop.

When done, tell me "review module 3".
