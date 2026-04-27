# PHP Basic Fundamentals 01 — My Journey

**Author:** Raihan
**Period:** April 2026
**Background:** JS / React / Next.js dev. First time writing PHP.

---

## 1. Overview

This was the work-assigned task to learn PHP fundamentals. The acceptance criteria from my senior were:

1. Write a PHP script using loops, conditions, and functions
2. Understand type juggling vs `strict_types` declaration
3. Use associative arrays to store and retrieve keyed data
4. Use common array functions: `array_map`, `array_filter`, `array_reduce`
5. Manipulate strings with `substr`, `str_replace`, `explode`, `implode`
6. Write and call reusable typed functions with return types

I worked through it as 8 modules (concept → exercise → review) instead of jumping straight to the capstone, so each AC has its own dedicated exercise plus a final report script that bundles everything. PHP version: 8.3.30 on Windows CLI.

Repo layout:
```
ROADMAP.md          ← curriculum
materials/          ← per-module concept explainers
exercises/          ← my code, one folder per module
STRUGGLES.log       ← every confusion I hit, written as it happened
JOURNEY.md          ← this file
```

---

## 2. What I learned (module by module)

### Module 1 — Hello PHP
Got comfortable with `<?php` tags, the missing close tag in pure-PHP files, and the difference between `echo` (statement, fast, default), `print` (expression, returns 1, rarely needed) and `var_dump` (debug tool that shows type + value). The biggest "huh, that's different" moment was realizing `echo` doesn't auto-add newlines or separators between arguments the way JS `console.log` does — every line of output needs its own `PHP_EOL` or `\n`. PHP gives you primitives; JS hands you a high-level logger.

```php
$greetings = "Hello, my name is Raihan";
$todaysDate = date("Y-m-d");
$php_version = PHP_VERSION;

echo $greetings, "\n";
echo "Today is ", $todaysDate, ".\n";
echo "PHP Version: ", $php_version;
```

### Module 2 — Types and `strict_types`
PHP is dynamically typed but with way more aggressive coercion than JS. `==` does type juggling (silently converts one side until they match, then compares); `===` is strict — different type means false, full stop. The famous trap is `"0" == false` is `true` and `(bool) "0" === false` — the literal string `"0"` is falsy in PHP, which JS doesn't share. `declare(strict_types=1)` opts a single file into runtime type-checking on function call boundaries. Without it, calling `double(3.14)` on `function double(int $n)` silently truncates 3.14 → 3 → returns 6. Seeing that `6` in my loose-mode demo was the moment strict_types stopped feeling optional.

```php
// juggling.php — proving == coerces while === doesn't
var_dump(0 == false);   // bool(true)  — false coerced to 0
var_dump(0 === false);  // bool(false) — int vs bool, type mismatch

// strict-demo.php — strict_types catches the bad call
declare(strict_types=1);

function double(int $n): int { return $n * 2; }

try {
    echo double("5");   // throws TypeError under strict_types
} catch (TypeError $e) {
    echo "Caught: ", $e->getMessage(), PHP_EOL;
}

// Same call without declare(strict_types=1):
//   double("5")   → silently coerces "5" → 5 → returns 10
//   double(3.14)  → silently truncates 3.14 → 3 → returns 6 (!)
```

### Module 3 — Control flow
`if/elseif/else`, ternary, null coalescing (`??`, same as JS) — all familiar. The new thing was `match` (PHP 8): a strict-comparison expression that returns a value, with no fall-through. Two flavors:
- `match($value)` for "compare one variable to a list of literal values" (used in calculator).
- `match(true)` for boolean conditions / range checks (used in FizzBuzz). Each arm's left side is an expression that evaluates to true/false.

`foreach ($arr as $key => $value)` is the workhorse loop — like `for...of` over `Object.entries()` in JS.

```php
// fizzbuzz.php — match(true) instead of if/else chain
foreach (range(1, 30) as $i) {
    $fizzBuzz = match (true) {
        $i % 3 === 0 && $i % 5 === 0 => "FizzBuzz",
        $i % 3 === 0                 => "Fizz",
        $i % 5 === 0                 => "Buzz",
        default                       => $i,
    };
    echo $fizzBuzz, PHP_EOL;
}
```

The order of arms matters — PHP returns the first one that matches, so `FizzBuzz` has to come before `Fizz`/`Buzz`.

### Module 4 — Associative arrays
PHP "arrays" are ordered maps. Same data structure handles indexed lists and key/value dicts. There is no separate "object literal" type — assoc arrays do that job. Big practical things:
- Reading a missing key emits a Warning + returns null. Always guard with `isset` or `??`.
- `array_column($users, "id")` extracts one field across an array of records — useful for building lookup tables before an `in_array` membership check.
- Always pass `true` as the third arg to `in_array` for strict comparison, same reasoning as `===`.

```php
// users.php — assoc-of-assoc storing user records
$users = [
    ["id" => 1, "name" => "Jack",   "email" => "jack@mail.com",   "role" => "admin"],
    ["id" => 2, "name" => "Raihan", "email" => "raihan@mail.com", "role" => "admin"],
    // ...
];

// addUser — guarded against duplicate ids using array_column lookup
function addUserColumnArray(array $users, array $new): array
{
    $existingIds = array_column($users, "id");
    if (in_array($new["id"], $existingIds, true)) {     // strict mode
        echo "Warning: user with id {$new['id']} already exists.", PHP_EOL;
        return $users;
    }
    $users[] = $new;
    return $users;
}
```

### Module 5 — `array_map` / `array_filter` / `array_reduce` + closures
The trio works like JS `.map / .filter / .reduce` BUT:
- Argument order is inconsistent: `array_map(fn, $arr)` vs `array_filter($arr, fn)`. No logic, just memorize.
- `array_filter` preserves original keys — wrap with `array_values()` if you need a clean 0-indexed result.
- `array_reduce`'s callback is `($carry, $item)` — no key/index passed.
- Arrow functions (`fn($x) => ...`) auto-capture outer variables; classic `function() {}` closures need `use ($var)` to capture them.

The biggest "click" moment: building a `[role => count]` map with `array_reduce` + an `isset` gate. Once I understood the pattern (init the key the first time, mutate the carry, return it), I reused the exact same shape in the capstone for `[category => total]`.

```php
// transform.php — the trio in action
$names   = array_map(fn($u) => $u["name"], $users);
$admins  = array_values(array_filter($users, fn($u) => $u["role"] === "admin"));
$totalLen = array_reduce($users, fn($carry, $u) => $carry + strlen($u["email"]), 0);

// the [role => count] reduce — the pattern that reappears in the capstone
$byRole = array_reduce($users, function ($carry, $user) {
    $role = $user["role"];
    if (!isset($carry[$role])) {
        $carry[$role] = 0;          // init only the first time we see this key
    }
    $carry[$role]++;
    return $carry;                  // critical — return the updated carry every time
}, []);                             // initial = [] because the result IS an array

// Arrow function auto-capturing $minLen from outer scope
$minLen = 5;
$longNames = array_values(array_filter($names, fn($name) => strlen($name) >= $minLen));
```

### Module 6 — Strings
PHP string functions are global, not methods. `strtolower`, `str_replace` (replaces ALL occurrences by default, can take arrays for both search and replace), `explode`/`implode` (JS `split`/`join`), `trim`, `substr`. Built `slugify()` as a typed function combining most of these. Watch out: `explode(" ", "a  b")` returns `["a", "", "b"]` — empty pieces are `""`, not `" "`.

PHP doesn't have method chaining like JS. The choices are step-by-step variables (most readable), nested function calls (PHP-idiomatic for short pipelines, hard past 3 levels), or wait for the pipe operator that's still in RFC limbo.

```php
// slugify.php — pipeline of string + array transforms
declare(strict_types=1);

function slugify(string $title): string
{
    $lowerCaseTitle = strtolower($title);
    $cleaned = str_replace([",", "!", "&", ".", ":", ";"], " ", $lowerCaseTitle);
    $words = array_values(array_filter(explode(" ", $cleaned), fn($s) => $s !== ""));
    return trim(implode("-", $words), "-");
}

slugify("Hello, World!");           // "hello-world"
slugify("PHP & JS: Type Juggling"); // "php-js-type-juggling"
```

### Module 7 — Typed functions
Type declarations on params and return type, including nullable (`?int`), unions (`int|string`), `void`, `never`. Default values must be constant expressions — can't default to `time()`. `match` for dispatch is an expression, so you can `throw` directly inside an arm: `default => throw new InvalidArgumentException(...)`. Caught `DivisionByZeroError` and `TypeError` (the strict-mode error from `add(1, "2")`) with try/catch. Bonus: variadic syntax `function sumAll(float ...$nums): float` accepts any number of args, accessed inside as a regular array.

```php
// calculator.php — typed functions, throw inside match, variadic
declare(strict_types=1);

function divide(float $a, float $b): float
{
    if ($b === 0.0) {
        throw new DivisionByZeroError("Cannot divide $a by zero");
    }
    return $a / $b;
}

function calculate(string $op, float $a, float $b): float
{
    return match ($op) {
        "+" => add($a, $b),
        "-" => subtract($a, $b),
        "*" => multiply($a, $b),
        "/" => divide($a, $b),
        default => throw new InvalidArgumentException("Unknown operator: $op"),
    };
}

// Variadic — accepts any number of floats
function sumAll(float ...$nums): float
{
    return array_reduce($nums, fn($carry, $n) => $carry + $n, 0.0);
}
sumAll(1.5, 2.5, 3.0);  // 7.0
```

### Module 8 — Capstone
Wired everything into a single revenue-report script. Filter refunded transactions with `array_filter`, slugify category names with my Module 6 `slugify`, group totals per category with `array_reduce`, format the report. Hits all six ACs in one file — that's the artifact for the senior to verify.

```php
// report.php — the core pipeline
declare(strict_types=1);
require_once __DIR__ . "/../06-strings/slugify.php";

// AC #5 — substr in dateOnly
function dateOnly(string $timestamp): string
{
    return substr($timestamp, 0, 10);
}

// Drop refunds BEFORE aggregating (this was my capstone bug — fixed)
$completedTxs = array_filter($transactions, fn($tx) => !$tx["refunded"]);

// Same [key => sum] reduce pattern from Module 5, applied to categories
function totalsByCategory(array $txs): array
{
    return array_reduce($txs, function ($carry, $tx) {
        $cat = slugify($tx["category"]);
        if (!isset($carry[$cat])) {
            $carry[$cat] = 0;
        }
        $carry[$cat] += $tx["amount"];
        return $carry;
    }, []);
}
```

Output:
```
=== Daily Revenue Report ===
Period: 2026-04-18 to 2026-04-21

Completed transactions : 6
Refunded transactions : 2
Revenue by category:
- books-media......... $56.00
- electronics......... $370.00
- home-goods.......... $15.00

Total revenue: $441.00
```

---

## 3. JS → PHP "aha" moments

- **PHP arrays are ordered maps.** `[1,2,3]` and `["name" => "Raihan"]` are the same data structure. Stop reaching for "object" mentally.
- **`echo` is `process.stdout.write`, not `console.log`.** No auto-newlines, no auto-separators. Output formatting is on you.
- **`==` does aggressive type juggling.** Use `===` everywhere by default, including in `in_array` (strict mode third arg).
- **Closures don't auto-capture in `function() {}` form.** You must list captured vars with `use ($var)`. Arrow functions `fn() => ...` do auto-capture (by value).
- **`array_filter` preserves keys.** Always `array_values(array_filter(...))` if you want a sequential array.
- **`array_map` callback first, `array_filter` array first.** PHP standard library is inconsistent. Read the docs every time, don't memorize.
- **Double-quoted strings interpolate `$variables`.** Want a literal `$`? Either escape it `\$` or use single quotes. (I got bit by this twice — once in Module 5's labels, again in Module 7's comment string.)
- **`'0'` is falsy in PHP, truthy in JS.** Single biggest gotcha for JS devs comparing falsy values.
- **`require_once` runs the entire included file.** Top-level demo code in a "library" file will fire — keep utility files free of side effects.
- **String functions are global, not methods.** No `$s->trim()->toLowerCase()`. You either step-by-step it or nest the calls inside-out.
- **`strict_types=1` must be the literal first statement of the file.** Per-file, not project-wide.

---

## 4. Struggles (the honest part)

These are real moments from `STRUGGLES.log` where I got stuck and had to be unstuck.

### M1: Why does `echo` smush outputs together?
Tried `echo $a; echo $b; echo $c;` and got `abc` on one line. Spent a minute thinking PHP was broken before realizing `echo` is a low-level write — it doesn't add anything between or after arguments. **Fix:** add `PHP_EOL` (or `, PHP_EOL`) to every line you want separated.

### M2: I had `==` and `===` flipped in my comments
My initial juggling exercise confidently explained that "`==` only compares the type" — the OPPOSITE of what's true. The code outputs were correct because PHP did the right thing; my mental model was upside down. The instructor caught it on first review. **Fix:** rewrote all five comments. Internalized the rule: *`==` coerces until types match, then compares values. `===` short-circuits on type mismatch.* Re-reading the same exercise with the right mental model made every result obvious.

### M2: "Does coercion happen on one side or both?"
Followed up because the rule felt vague. Turns out it depends on the type pair — for int↔bool, the int gets cast to bool; for string↔int (PHP 8+), string gets parsed as a number; for null↔anything, the other side gets cast to bool. Practical takeaway: **comparing against `true`/`false` with `==` is almost always wrong** because the other side gets cast to bool and you lose all type info. Just write `if ($x)`.

### M3: `match` looked unfamiliar
The syntax `$result = match (...) { ... };` didn't slot into any JS shape I knew. The breakthrough: `match` is an EXPRESSION (returns a value you assign), not a statement. And the `match(true)` form turns it into a "first arm whose condition evaluates to true wins" pattern — perfect for FizzBuzz and any range-check chain.

### M4: My `addUser` was appending Rico 5 times
Wrote the duplicate-check inside the foreach loop, so for every NON-matching id I appended again. Also `foreach` was iterating over the growing array, making it worse. **Fix:** scan the full array first, return early on a duplicate, append ONCE outside the loop. The instructor showed me the alternative: `array_column($users, "id")` to extract ids, then `in_array($newId, $ids, true)`. Cleaner — no manual loop.

### M5: I didn't actually understand `array_reduce`
This was my biggest knowledge gap. I wrote `return $user["role"];` inside the reducer and was confused that the result was just the string `"member"`. **The lesson:** the callback's return value IS the next iteration's `$carry`. If you return a string, your carry becomes a string and everything you accumulated before is gone. Three rules:
1. Always include `$carry` in your return.
2. The initial value's type IS the result's type. Want an array? Initial = `[]`. Want a sum? Initial = `0`.
3. Each step transforms the carry based on the current item.

The `if (!isset($carry[$key])) { $carry[$key] = 0; } $carry[$key]++;` pattern then made sense — the `isset` gate is "init only the first time I see this key, otherwise just increment." Same idea as JS `obj[key] ??= 0; obj[key]++;`.

### M5: `array_filter` not actually reindexing
Filtered admins from the users list and the result keys were `[0, 1]`. Looked reindexed but wasn't — admin Jack was originally at index 0 and Raihan at index 1, so it was coincidence. The instructor told me to swap users around to prove it. **Fix:** `array_values(array_filter(...))`. From then on I wrapped every filter in `array_values` whenever I wanted a clean sequence — including in the capstone.

### M6: Empty pieces from `explode` were `""` not `" "`
Tried filtering `fn($s) => $s !== " "` and the empties survived. Empty pieces from `explode` are the empty string `""` — zero characters between two delimiters — not a space. **Fix:** `$s !== ""`. Or `array_filter($arr)` with no callback (drops all falsy values), but that would also drop the literal `"0"`.

### M7: `default => throw new InvalidArgumentException` (no parens)
Forgot to instantiate the exception class. Just naming the class isn't throwing it. PHP didn't catch this at parse time which made the bug confusing. **Fix:** `throw new InvalidArgumentException("Unknown operator: $op")`.

### M8 (capstone): Refunds were inflating my totals
Computed `$completed` and `$refunded` counts via `array_filter` correctly, then passed the unfiltered `$transactions` to `totalsByCategory`. Result: Electronics showed $469 instead of $370 (the refunded $99 transaction was still counted). **Fix:** filter once, reuse the filtered array for the totals. Conceptually I knew filtering was needed; I just plumbed the wrong variable downstream. Lesson: when you compute a filtered version of something for one purpose, ask "should the rest of the pipeline use the filtered version too?"

### M8: Weird `implode/explode` roundtrip in `formatReport`
The spec said use `implode` to print the report. I built the report as one big string with `\n` baked in, then exploded and reimploded with `PHP_EOL`. It worked but felt wrong, and I flagged it. The instructor confirmed: the natural pattern is to build an array of lines (`$lines[] = "line text"`) and `implode(PHP_EOL, $lines)` once at the end. The exercise rubric was misleading me — `implode` and `explode` are usually used in different parts of the codebase, not as a roundtrip. Keeping my hack as-is for the deliverable, with a note here.

---

## 5. Open questions for my senior

1. **Project conventions for `strict_types`** — should every file in our codebase declare it, or only certain layers? Per-file feels error-prone if someone forgets.
2. **Classes vs plain functions** — the curriculum was 100% functional. When does the team reach for OOP / classes? For domain models, services, both?
3. **String safety** — I used `strlen` everywhere, which counts bytes. For real user input with non-ASCII characters (Indonesian names, emoji), should I default to `mb_strlen` / `mb_substr` / `mb_strtolower`?
4. **Slug generation in production** — my `slugify` only handles a hardcoded set of punctuation. The `preg_replace('/[^a-z0-9]+/', '-', $s)` regex version is more robust. Any reason NOT to use regex for this?
5. **`require_once` patterns** — including the slugify file in the capstone also ran its demo code. What's the team's structure for utility files vs runnable scripts? Namespaces? Composer autoload?
6. **Error handling depth** — I caught `\Throwable` in some places (too broad) and specific `TypeError` / `DivisionByZeroError` in others. What's the team's policy on catch granularity?

---

## 6. Capstone walkthrough — `exercises/08-capstone/report.php`

The whole script ticks every AC. Quick map:

| Function / line | What it does | AC ticked |
|---|---|---|
| `declare(strict_types=1);` (line 4) | Opt the file into runtime type checking | #2 |
| `require_once .../slugify.php` (line 5) | Reuse Module 6's typed `slugify` function | #5, #6 |
| `$transactions` array (lines 7–16) | Hardcoded input — assoc array of records | #3 |
| `dateOnly(string): string` (line 19) | Trims a timestamp to its date part using `substr` | #5, #6 |
| `array_map` over `ts` (line 26) | Extract timestamps for min/max date detection | #4 |
| `array_filter` for completed/refunded (lines 36, 39, 70) | Two `count(filter)` calls plus the actual filtered list reused for totals | #4 |
| `totalsByCategory(array): array` (line 42) | Slugifies categories, then `array_reduce` to build `[slug => sum]` | #4, #5, #6 |
| `formatReport(...)` (line 72) | Typed function building the printable report; uses `str_pad`, `number_format` | #1, #6 |
| `explode("\n", ...)` then `implode(PHP_EOL, ...)` (lines 93, 95) | Final output assembly | #5 |

**Sample output:**
```
=== Daily Revenue Report ===
Period: 2026-04-18 to 2026-04-21

Completed transactions : 6
Refunded transactions : 2
Revenue by category:
- books-media......... $56.00
- electronics......... $370.00
- home-goods.......... $15.00

Total revenue: $441.00
```

**Things I'd improve if I rewrote it now:**
1. Build `formatReport` as an array of lines, single `implode` at the end. Drop the explode/implode hack.
2. Inline `slugify` instead of `require_once` so the capstone is one self-contained file (avoids the include-side-effect of the demo echoes appearing in output).
3. Move the `array_filter` for completed transactions to a typed helper like `function completedOnly(array $txs): array { ... }` to make the pipeline more explicit.
4. Use `mb_*` string functions for non-ASCII safety.

---

## 7. Acceptance criteria checklist (proof for the senior)

| # | Criterion | Where to look |
|---|---|---|
| 1 | Loops / conditions / functions | [exercises/03-control-flow/fizzbuzz.php](exercises/03-control-flow/fizzbuzz.php) (loops + match), [exercises/07-typed-fns/calculator.php](exercises/07-typed-fns/calculator.php) (functions), [exercises/08-capstone/report.php](exercises/08-capstone/report.php) (all combined) |
| 2 | Type juggling vs `strict_types` | [exercises/02-types/juggling.php](exercises/02-types/juggling.php) (5 surprising loose comparisons), [exercises/02-types/strict-demo.php](exercises/02-types/strict-demo.php) and [exercises/02-types/strict-demo-loose.php](exercises/02-types/strict-demo-loose.php) (silent coercion `3.14 → 3 → 6` vs TypeError) |
| 3 | Associative arrays | [exercises/04-arrays/users.php](exercises/04-arrays/users.php) (5-user assoc array, foreach over keyed records, `addUser` with duplicate guard via `array_column` + `in_array(strict)`) |
| 4 | `array_map` / `array_filter` / `array_reduce` | [exercises/05-array-fns/transform.php](exercises/05-array-fns/transform.php) (all three plus arrow function capturing outer var) |
| 5 | `substr` / `str_replace` / `explode` / `implode` | [exercises/06-strings/slugify.php](exercises/06-strings/slugify.php) (`str_replace`, `explode`, `implode`, `trim`); [exercises/08-capstone/report.php](exercises/08-capstone/report.php) (`substr` in `dateOnly`, `explode`/`implode` in report assembly) |
| 6 | Typed functions with return types | [exercises/07-typed-fns/calculator.php](exercises/07-typed-fns/calculator.php) (5 typed functions with `float`/`string` params and return types, variadic `sumAll`); [exercises/06-strings/slugify.php](exercises/06-strings/slugify.php), [exercises/08-capstone/report.php](exercises/08-capstone/report.php) (every helper typed) |

---

## 8. Closing thoughts

PHP felt foreign for the first two modules because of the type juggling weirdness and the "global functions instead of methods" approach. By Module 5 the patterns started feeling natural — and by the capstone, building `[category => total]` with `array_reduce` came out almost the same way I would've written it in JS, just with PHP's syntax and stricter types.

The biggest wins from doing this with explicit modules instead of jumping to the capstone:
- The Module 5 `[role => count]` pattern was a direct rehearsal for the Module 8 `[category => total]` pattern. Same shape, different domain.
- Catching my flipped `==`/`===` mental model in Module 2 saved me from carrying a wrong assumption into every later module.
- Writing `slugify` as a standalone exercise meant the capstone got a known-working dependency for free.

Things I'm still uncertain about: classes/OOP, namespaces, Composer/autoloading, framework conventions (Laravel? Symfony?), proper error handling layers, and how production PHP teams structure projects. Those are open questions for my senior.

Code, materials, exercises, and struggles log all live at: https://github.com/raihank43/php-basic-fundamentals-01
