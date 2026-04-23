# Module 1 — Hello PHP

> Goal: get comfortable running PHP from the terminal and writing a script that prints things.

## 1. The `<?php` tag

Every PHP file starts with `<?php`. Anything before it is sent to output as-is (relevant for templating in HTML files). For pure PHP files (CLI scripts), the convention is:

```php
<?php

// your code here
```

No closing `?>` tag in pure-PHP files — it prevents accidental whitespace from being sent to output.

**JS analogue:** there isn't one. JS files don't need a marker because JS is always JS. PHP was born as an HTML-templating language, so it needs a "PHP starts here" tag.

## 2. Statements and `echo`

Every statement ends with `;`. Output uses `echo`:

```php
<?php
echo "Hello, PHP\n";
echo "Two", " ", "args", "\n";   // echo can take multiple args
print "print returns 1\n";        // print is an expression, echo is not
```

`var_dump()` is your debugging best friend — it prints type AND value:

```php
var_dump(42);          // int(42)
var_dump("42");        // string(2) "42"
var_dump(true);        // bool(true)
var_dump([1, 2, 3]);   // array(3) { [0]=> int(1) ... }
```

**JS analogue:** `echo` ≈ `process.stdout.write`. `var_dump` ≈ `console.log` but with type info baked in.

## 3. Variables

Variables start with `$`. They are case-sensitive. No `let`/`const`/`var` keyword.

```php
$name = "Raihan";
$age = 25;
$isLearning = true;
```

**Gotcha:** `$Name` and `$name` are different variables. But function names are case-INsensitive: `strlen("hi")` and `STRLEN("hi")` both work (don't rely on this — use lowercase).

## 4. Running PHP from the CLI

You're on Windows with PHP 8.3 already installed. From the terminal:

```bash
php script.php           # run a file
php -r 'echo 1 + 1;'     # run an inline expression
php -a                   # interactive REPL (limited but useful)
php --version            # check PHP version
```

## 5. Built-ins you'll need today

- `date("Y-m-d")` → today's date as `2026-04-23`
- `PHP_VERSION` → constant string like `"8.3.30"`
- `PHP_EOL` → newline constant (use this instead of `"\n"` for portability)

## 6. Comments

```php
// single-line
# also single-line (rare, but valid)
/* multi
   line */
```

---

## Exercise

Create `exercises/01-hello/hello.php`. It should print:

```
Hello, my name is Raihan.
Today is 2026-04-23.
PHP version: 8.3.30
```

Use: `echo`, `date()`, the `PHP_VERSION` constant, and `PHP_EOL` for newlines.

Run it with:
```bash
php exercises/01-hello/hello.php
```

When done, tell me "review module 1".
