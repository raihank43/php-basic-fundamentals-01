# PHP Basic Fundamentals 01 — Learning Roadmap

> Audience: JS/React dev learning PHP for the first time.
> Environment: PHP 8.3 CLI on Windows.
> Goal: Hit all 6 acceptance criteria from the senior's task, with code to prove it.

## How this works

Each module has:
- **Material** — concept explainer in `materials/<module>.md` (read this first)
- **Exercise** — code you write yourself in `exercises/<module>/`
- **Review checkpoint** — Claude reviews your code before moving on

Don't skip ahead. The modules build on each other.

| # | Material |
|---|---|
| 1 | [01-hello-php.md](materials/01-hello-php.md) |
| 2 | [02-types-and-strict.md](materials/02-types-and-strict.md) |
| 3 | [03-control-flow.md](materials/03-control-flow.md) |
| 4 | [04-arrays.md](materials/04-arrays.md) |
| 5 | [05-higher-order-array-fns.md](materials/05-higher-order-array-fns.md) |
| 6 | [06-strings.md](materials/06-strings.md) |
| 7 | [07-typed-functions.md](materials/07-typed-functions.md) |
| 8 | [08-capstone.md](materials/08-capstone.md) |
| 9 | [09-journey-doc.md](materials/09-journey-doc.md) |

---

## Module 1 — Hello PHP, syntax & runtime
**Maps to AC:** #1 (loops/conditions/functions)

Concepts:
- `<?php` tag, statement terminators (`;`), `echo` vs `print` vs `var_dump`
- Variables (`$name`), case sensitivity rules (vars are case-sensitive, functions are NOT)
- Running PHP from CLI (`php script.php`) vs serving via a web server
- Comments (`//`, `#`, `/* */`)
- JS analogue: think of `<?php` as the `"use strict"` of an HTML-templated file — but here we're CLI-only

Exercise: `exercises/01-hello/hello.php`
- Print your name, today's date, and the PHP version using built-ins.

---

## Module 2 — Types, type juggling, and `strict_types`
**Maps to AC:** #2

Concepts:
- Scalar types: `int`, `float`, `string`, `bool`; compound: `array`, `object`; special: `null`
- `==` (loose, type juggling) vs `===` (strict) — the famous `"0" == false` trap
- `declare(strict_types=1);` — must be the first statement; affects function arg/return coercion
- `var_dump` vs `gettype` for inspecting types
- JS analogue: `==` vs `===` is the same idea, but PHP's juggling rules are *worse*. `strict_types` is closer to TypeScript at runtime.

Exercise: `exercises/02-types/juggling.php`
- Demonstrate at least 5 surprising loose-comparison results, then re-run them with `===` and explain.
- Write the same script twice — once without `declare(strict_types=1)` and once with it — and show how a function call with a wrong type behaves differently.

---

## Module 3 — Control flow: conditions & loops
**Maps to AC:** #1

Concepts:
- `if / elseif / else`, ternary `?:`, null-coalescing `??`, short-ternary `?:`
- `match` (PHP 8) vs `switch` — `match` uses strict comparison, returns a value
- Loops: `for`, `while`, `do-while`, `foreach ($arr as $value)`, `foreach ($arr as $key => $value)`
- `break`, `continue` (with optional level: `continue 2`)
- JS analogue: `??` works like JS `??`. `match` is closer to a switch expression. `foreach` with `key => value` is like `for...of` over `Object.entries()`.

Exercise: `exercises/03-control-flow/fizzbuzz.php`
- Classic FizzBuzz 1–50, but using `match(true)` instead of if/else chain.

---

## Module 4 — Arrays (indexed + associative)
**Maps to AC:** #3

Concepts:
- PHP arrays are *ordered maps* — same data structure for indexed lists and key/value dicts
- Creating: `[1, 2, 3]`, `['name' => 'Raihan', 'role' => 'JS dev']`
- Accessing, adding, updating, deleting (`unset($arr['key'])`)
- `array_keys`, `array_values`, `in_array`, `array_key_exists`, `isset`
- `count()`, nested arrays, destructuring with `[$a, $b] = $arr`
- JS analogue: closer to `Map` than to `Object`, but written with the same `[]` syntax. There's no separate "object literal" — assoc arrays do that job here.

Exercise: `exercises/04-arrays/users.php`
- Build an associative array of 5 users (id, name, email, role).
- Print each user's email, count how many have role `"admin"`, and add one more user safely without duplicating an existing id.

---

## Module 5 — Higher-order array functions
**Maps to AC:** #4

Concepts:
- `array_map(fn, array)` — transform each element (note: callback first, unlike JS!)
- `array_filter(array, fn)` — keep elements where fn returns truthy (callback second!)
- `array_reduce(array, fn, initial)` — fold to a single value
- Anonymous functions: `function ($x) { ... }` and arrow functions `fn ($x) => $x * 2`
- `use ($var)` clause to capture variables (closures don't capture by default like JS)
- JS analogue: same idea as `.map / .filter / .reduce`, but: (a) argument order varies per function — read the docs, (b) `array_map` doesn't pass the index by default, (c) closures need explicit `use` for outer vars.

Exercise: `exercises/05-array-fns/transform.php`
- Given the users array from Module 4:
  - Use `array_map` to extract just the names.
  - Use `array_filter` to keep only admins.
  - Use `array_reduce` to compute the total length of all email strings.

---

## Module 6 — String manipulation
**Maps to AC:** #5

Concepts:
- `substr($str, $start, $length)` — note: negative indexes count from end
- `str_replace($search, $replace, $subject)` — `$search` and `$replace` can be arrays
- `explode($delimiter, $str)` → array (like JS `split`)
- `implode($glue, $array)` → string (like JS `join`)
- Bonus: `strlen`, `strtolower`, `strtoupper`, `trim`, `str_contains` (PHP 8)
- Single vs double quotes: only double quotes interpolate `$variables`. Heredoc `<<<EOT` for multiline.
- JS analogue: same intent as JS string methods, but PHP's are global functions (not methods on a string object).

Exercise: `exercises/06-strings/slugify.php`
- Write a function `slugify(string $title): string` that turns `"Hello, World! PHP & JS"` into `"hello-world-php-js"`.
- Use at least: `strtolower`, `str_replace`, `explode`, `implode`, and either `trim` or `substr`.

---

## Module 7 — Typed functions & return types
**Maps to AC:** #6

Concepts:
- Function declaration: `function name(type $param): returnType { ... }`
- Nullable types `?string`, union types `int|string` (PHP 8), `void`, `never`, `mixed`
- Default parameter values, named arguments (PHP 8): `slugify(title: "...")`
- Variadics: `function sum(int ...$nums): int`
- Pure vs impure: PHP doesn't enforce purity, but document it
- `declare(strict_types=1)` interacts with these — required for actual type enforcement
- JS analogue: TypeScript signatures, but enforced at runtime not compile time.

Exercise: `exercises/07-typed-fns/calculator.php`
- Build a small calculator module with typed functions: `add`, `subtract`, `multiply`, `divide`. Use `declare(strict_types=1)`. `divide` should return `float` and throw on divide-by-zero.

---

## Module 8 — Capstone
**Maps to ALL AC**

Build `exercises/08-capstone/report.php`:
- Read a hardcoded array of "transactions" (id, user, amount, category, timestamp string).
- Use `explode` to parse the timestamp into date parts.
- Use `array_filter` to drop refunded transactions.
- Use `array_map` to normalize categories (lowercase, hyphenated — reuse your `slugify`).
- Use `array_reduce` to compute total revenue per category (associative array result).
- Print a clean text report using `implode` and `str_replace` for formatting.
- All helper functions are typed with `declare(strict_types=1)`.

This single script must demonstrate every acceptance criterion. Your senior should be able to read this one file and tick all six boxes.

---

## Module 9 — Documentation deliverable

Create `JOURNEY.md` covering:
1. **What I learned** — module by module, in your own words
2. **JS → PHP "aha" moments** — comparisons that clicked
3. **Struggles** — bugs you hit, why they confused you, how you fixed them (we'll log these as we go in `STRUGGLES.log`)
4. **Open questions** — things still fuzzy, to ask your senior
5. **Capstone walkthrough** — annotated explanation of `report.php`

---

## Progress tracker

- [ ] Module 1 — Hello PHP
- [ ] Module 2 — Types & strict_types
- [ ] Module 3 — Control flow
- [ ] Module 4 — Arrays
- [ ] Module 5 — Higher-order array fns
- [ ] Module 6 — Strings
- [ ] Module 7 — Typed functions
- [ ] Module 8 — Capstone
- [ ] Module 9 — Journey doc
