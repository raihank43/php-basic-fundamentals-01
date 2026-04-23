# Module 7 — Typed Functions & Return Types

> Goal: write PHP functions that feel TypeScript-y — clear signatures, real type errors when misused.

## 1. Anatomy of a typed function

```php
declare(strict_types=1);

function greet(string $name, int $times = 1): string {
    return str_repeat("Hello, $name! ", $times);
}
```

Breakdown:
- `string $name` — parameter type (before the variable)
- `int $times = 1` — parameter with default value
- `: string` — return type (after the parameter list)
- `declare(strict_types=1)` — make those types actually strict

## 2. The scalar types

`int`, `float`, `string`, `bool`, `array`, `object`, `callable`, `iterable`, plus class names for object params.

Without `strict_types`, PHP silently coerces compatible scalars. With it, passing a mismatched type throws `TypeError`.

## 3. Nullable and union types

```php
// Nullable — ? means "this type OR null"
function findUser(?int $id): ?array {
    if ($id === null) return null;
    // ...
}

// Union types (PHP 8+)
function stringOrInt(int|string $x): string {
    return (string) $x;
}

// Nullable via union (equivalent to ?array)
function maybeArray(): array|null { ... }
```

**JS analogue:** `?int` is TypeScript's `number | null | undefined` (sort of). `int|string` is `number | string`.

## 4. Special return types

```php
function log(string $msg): void {        // returns nothing — don't return a value
    echo $msg;
}

function halt(string $reason): never {   // function never returns (throws/exits)
    throw new RuntimeException($reason);
}

function anything(): mixed { ... }       // any type — use sparingly
```

## 5. Named arguments (PHP 8+)

You can pass arguments by parameter name — great for boolean flags and optional params:

```php
function slugify(string $title, string $sep = '-', bool $lowercase = true): string { ... }

slugify(title: "Hello World", sep: "_");
slugify("Hello", lowercase: false);   // mix positional + named
```

## 6. Variadics — accept any number of args

```php
function sum(int ...$nums): int {
    return array_sum($nums);
}

sum(1, 2, 3);        // 6
sum(...[1, 2, 3]);   // 6 — spread operator on call site
```

## 7. Default values

```php
function make(string $name, array $tags = [], ?int $priority = null): array {
    return compact('name', 'tags', 'priority');
}
```

**Gotcha:** defaults must be constant expressions — you can't default to the result of a function call (e.g. `$now = time()` as a default is NOT allowed).

## 8. Referencing vs copying

PHP passes arguments **by value** by default (arrays are COPIED into the function — different from JS object reference passing). To pass by reference use `&`:

```php
function push(array &$arr, mixed $item): void {
    $arr[] = $item;
}

$list = [1, 2];
push($list, 3);
var_dump($list);  // [1, 2, 3]
```

**JS analogue:** JS passes object references — mutations inside a function are visible outside. PHP copies arrays — mutations stay local unless you use `&`. Prefer returning new arrays over mutating via reference, same as in JS.

## 9. Throwing and catching

```php
function divide(int $a, int $b): float {
    if ($b === 0) {
        throw new DivisionByZeroError("Cannot divide $a by zero");
    }
    return $a / $b;
}

try {
    echo divide(10, 0);
} catch (DivisionByZeroError $e) {
    echo "Oops: ", $e->getMessage();
}
```

---

## Exercise

Create `exercises/07-typed-fns/calculator.php`.

Top of file: `declare(strict_types=1);`.

Build four typed functions:

```php
function add(float $a, float $b): float
function subtract(float $a, float $b): float
function multiply(float $a, float $b): float
function divide(float $a, float $b): float
```

Requirements:
1. `divide` throws `DivisionByZeroError` if `$b === 0.0`.
2. Write a fifth function `calculate(string $op, float $a, float $b): float` that dispatches to the right function using `match`. Unknown `$op` throws `InvalidArgumentException`.
3. Demo-run at the bottom: call each operation, plus a try/catch around `divide(5, 0)` and `calculate("mod", 1, 2)`.
4. Try calling `add(1, "2")` with and without strict_types — comment what happens for each.

**Stretch:** add a variadic `sumAll(float ...$nums): float` using `array_reduce`.

When done, tell me "review module 7".
