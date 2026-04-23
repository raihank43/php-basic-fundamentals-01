# Module 5 — Higher-Order Array Functions

> Goal: use `array_map`, `array_filter`, `array_reduce` fluently, and understand PHP closures.

## 1. Closures (anonymous functions)

Two forms in PHP:

### Classic `function` form
```php
$double = function ($x) {
    return $x * 2;
};
echo $double(5);  // 10
```

### Arrow function (PHP 7.4+) — single expression, auto-captures
```php
$double = fn ($x) => $x * 2;
echo $double(5);  // 10
```

### The `use` clause — PHP closures DON'T capture automatically
```php
$factor = 3;

// function form — MUST explicitly declare captured vars with `use`
$scale = function ($x) use ($factor) {
    return $x * $factor;
};

// arrow form — auto-captures by value, no `use` needed
$scale = fn ($x) => $x * $factor;
```

**JS analogue:** JS closures capture every in-scope variable automatically. PHP's classic closure does NOT — you list what you need via `use`. Arrow functions capture implicitly (by value only).

**Capture by reference** — if you want the closure to see future changes:
```php
$counter = 0;
$inc = function () use (&$counter) {   // note the &
    $counter++;
};
$inc(); $inc();
echo $counter;  // 2
```

## 2. `array_map` — transform

```php
array_map(callable $fn, array ...$arrays): array
```

**Argument order:** callback FIRST, array second (opposite of JS!).

```php
$nums = [1, 2, 3, 4];
$doubled = array_map(fn ($n) => $n * 2, $nums);
// [2, 4, 6, 8]
```

With associative arrays, **keys are preserved** but the callback only receives the VALUE (not the key, unlike JS):

```php
$prices = ['apple' => 1.0, 'banana' => 0.5];
$withTax = array_map(fn ($p) => $p * 1.1, $prices);
// ['apple' => 1.1, 'banana' => 0.55]
```

To iterate with keys, pass a second array:
```php
array_map(fn ($k, $v) => "$k=$v", array_keys($prices), array_values($prices));
```

## 3. `array_filter` — keep elements

```php
array_filter(array $array, ?callable $callback = null): array
```

**Argument order:** array FIRST, callback second (opposite of `array_map`!).

```php
$nums = [1, 2, 3, 4, 5];
$evens = array_filter($nums, fn ($n) => $n % 2 === 0);
// [1 => 2, 3 => 4]
```

**Gotcha:** keys are PRESERVED. `$evens` is `[1 => 2, 3 => 4]`, NOT `[2, 4]`. To reindex, wrap with `array_values()`:

```php
$evens = array_values(array_filter($nums, fn ($n) => $n % 2 === 0));
// [2, 4]
```

With no callback, `array_filter` removes falsy values — useful, but remember PHP's falsy rules include `"0"`:

```php
array_filter(['a', '', 0, '0', null, 'b']);  // ['a', 'b'] — "0" is dropped!
```

## 4. `array_reduce` — fold

```php
array_reduce(array $array, callable $callback, mixed $initial = null): mixed
```

**Argument order:** array first, callback second, initial third.
Callback signature: `function ($carry, $item)` — carry first, item second (same as JS `reduce`).

```php
$nums = [1, 2, 3, 4];
$sum = array_reduce($nums, fn ($carry, $n) => $carry + $n, 0);
// 10

$max = array_reduce($nums, fn ($carry, $n) => $n > $carry ? $n : $carry, PHP_INT_MIN);
// 4
```

**Gotcha:** the callback is called with `$item` as the value only — no key, no index. If you need keys, use `foreach` or `array_walk` instead.

## 5. Quick JS cheat-sheet

| JS | PHP |
|---|---|
| `arr.map(fn)` | `array_map(fn, $arr)` |
| `arr.filter(fn)` | `array_filter($arr, fn)` |
| `arr.reduce(fn, init)` | `array_reduce($arr, fn, $init)` |
| `arr.forEach(fn)` | `foreach ($arr as $x) {...}` (or `array_walk`) |
| `arr.find(fn)` | `current(array_filter(...))` — ugly, often just foreach |
| `arr.some(fn)` | manual `foreach`, or `array_filter(...)` + `count` |
| `arr.includes(x)` | `in_array($x, $arr, true)` |

---

## Exercise

Create `exercises/05-array-fns/transform.php`.

Reuse (or redefine) the `$users` array from Module 4.

1. Use `array_map` to produce `$names` — an array of just names.
2. Use `array_filter` to produce `$admins` — users whose role is `"admin"`. Reindex the result.
3. Use `array_reduce` to produce `$totalEmailLen` — the sum of the lengths of every user's email string.
4. Use `array_reduce` to produce `$byRole` — an associative array mapping role → count. Example output: `['admin' => 2, 'member' => 3]`.
5. Write an arrow function that captures a `$minLen` variable from outer scope and filters names to only those at least that long.

Print each result with `var_dump` or `print_r`.

**Stretch:** rewrite #4 as a one-liner that combines `array_map` and `array_count_values`.

When done, tell me "review module 5".
