# Module 2 — Types, Type Juggling, and `strict_types`

> Goal: understand why `"0" == false` is `true` in PHP, and how to opt out of that madness.

## 1. PHP's type system

PHP is **dynamically typed** (like JS) but has richer runtime type support than JS does:

| Category | Types |
|---|---|
| Scalar | `int`, `float`, `string`, `bool` |
| Compound | `array`, `object`, `callable`, `iterable` |
| Special | `null`, `resource`, `mixed`, `void`, `never` |

Check a value's type with `gettype($x)` or `var_dump($x)`.

## 2. Type juggling (the default)

PHP converts types automatically in many contexts — *much more aggressively* than JS:

```php
var_dump("5" + 3);        // int(8)     — string becomes int
var_dump("5" . 3);        // string(2) "53" — . is string concat
var_dump("abc" + 1);      // int(1)     — "abc" becomes 0 (in PHP 8 this warns)
var_dump(0 == "a");       // bool(false) in PHP 8  (was true in PHP 7!)
var_dump(0 == false);     // bool(true)
var_dump("0" == false);   // bool(true)
var_dump("" == false);    // bool(true)
var_dump(null == false);  // bool(true)
var_dump([] == false);    // bool(true)
```

**JS analogue:** this is PHP's version of JS's `==`, but PHP historically had even weirder rules. PHP 8 cleaned up some of the worst ones (string-to-number comparisons now behave sanely).

## 3. Strict comparison: `===`

`===` compares value **AND** type. No juggling. Use this by default.

```php
var_dump(0 === false);     // bool(false)
var_dump("5" === 5);       // bool(false)
var_dump(5 === 5);         // bool(true)
var_dump(null === null);   // bool(true)
```

**Rule of thumb:** always use `===` and `!==` unless you have a specific reason not to. Exactly like `===` vs `==` advice in JS, only the stakes are higher.

## 4. `declare(strict_types=1)`

This is PHP's biggest opt-in safety feature. Without it, function calls quietly coerce arguments:

```php
<?php
function addOne(int $n): int {
    return $n + 1;
}

echo addOne("5");    // 6 — PHP silently casts "5" to int
echo addOne("5.9");  // 6 — truncated!
echo addOne(true);   // 2 — true becomes 1
```

With strict types, the *same code* throws `TypeError`:

```php
<?php
declare(strict_types=1);   // MUST be the very first statement

function addOne(int $n): int {
    return $n + 1;
}

addOne("5");  // TypeError: addOne(): Argument #1 ($n) must be of type int, string given
addOne(5);    // OK
```

**Rules for `declare(strict_types=1)`:**
- Must be the literal first statement of the file (only comments and whitespace allowed before).
- It's **per-file** — only affects the file that declares it, at the *call site*.
- Affects scalar parameter types AND return types.
- Does NOT affect internal coercions like `"5" + 3`.

**JS analogue:** closest thing is TypeScript — but TS checks at compile time and the output JS still coerces at runtime. `strict_types` enforces at runtime.

## 5. The "falsy" values in PHP

Both `==` and `if ($x)` treat these as falsy:

- `false`
- `0`, `0.0`
- `"0"` (yes, the string "0" is falsy — **JS doesn't do this**)
- `""` (empty string)
- `null`
- `[]` (empty array)
- undefined variables (warning in PHP 8+)

**JS dev trap:** in JS, `Boolean("0")` is `true`. In PHP, `(bool) "0"` is `false`. This bites JS devs often.

## 6. Casting explicitly

```php
(int) "42";         // 42
(float) "3.14";     // 3.14
(string) 42;        // "42"
(bool) "hello";     // true
(array) "x";        // ["x"]
intval("42abc");    // 42  (function form)
strval(42);         // "42"
```

---

## Exercise

Create two files:

**`exercises/02-types/juggling.php`** — NO strict_types. Demonstrate at least 5 surprising loose-comparison results. For each, print both the loose (`==`) and strict (`===`) outcome and a one-line comment explaining what PHP did.

**`exercises/02-types/strict-demo.php`** — WITH `declare(strict_types=1);`. Define a function `double(int $n): int`. Call it with:
1. an `int` (should work)
2. a string like `"5"` (should throw — wrap in try/catch and print the error message)
3. a `float` like `3.14` (should throw)

Run both and compare.

When done, tell me "review module 2".
