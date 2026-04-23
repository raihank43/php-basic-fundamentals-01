# Module 6 — String Manipulation

> Goal: manipulate strings fluently with PHP's built-ins. None of these are methods on a string object — they're all global functions.

## 1. Quoting rules

```php
$name = "Raihan";

echo 'Hello $name';    // Hello $name   — single quotes: literal
echo "Hello $name";    // Hello Raihan  — double quotes: interpolate
echo "Hello {$name}s"; // Hello Raihans — braces disambiguate
echo 'Hello ' . $name; // Hello Raihan  — . is the concat operator
```

**Rule of thumb:** use single quotes unless you need interpolation or escape sequences (`\n`, `\t`). Faster to parse and no surprises.

**Heredoc / Nowdoc** for multiline strings:
```php
$doc = <<<EOT
Hello $name,
this is a multiline string.
EOT;

$doc = <<<'EOT'
Literal $name — no interpolation here.
EOT;
```

## 2. `substr` — extract a slice

```php
substr(string $s, int $offset, ?int $length = null): string

substr("Hello", 0, 3)    // "Hel"
substr("Hello", 1)       // "ello"   — to end
substr("Hello", -3)      // "llo"    — negative = count from end
substr("Hello", -3, 2)   // "ll"
```

**JS analogue:** roughly `str.slice(start, start+length)` — but watch out, JS `slice` takes start+end, PHP `substr` takes start+length.

## 3. `str_replace` — find and replace

```php
str_replace(
    string|array $search,
    string|array $replace,
    string|array $subject,
    int &$count = null
): string|array

str_replace("world", "PHP", "hello world");    // "hello PHP"
str_replace(["a", "e", "i", "o", "u"], "*", "hello world"); // "h*ll* w*rld"
str_replace(["php", "js"], ["PHP", "JS"], "I use php and js"); // "I use PHP and JS"
```

- Replaces ALL occurrences (unlike JS `replace` which replaces first unless regex/global).
- Case-sensitive. Use `str_ireplace` for case-insensitive.

## 4. `explode` — string → array (like JS `split`)

```php
explode(string $separator, string $string, int $limit = PHP_INT_MAX): array

explode(",", "a,b,c,d");       // ["a", "b", "c", "d"]
explode(",", "a,b,c,d", 2);    // ["a", "b,c,d"]
explode("-", "hello");         // ["hello"]  — no separator found: 1-element array
```

**Gotcha:** separator is REQUIRED and non-empty. `explode("", "hi")` throws an error. To split into individual chars use `str_split("hi")`.

## 5. `implode` — array → string (like JS `join`)

```php
implode(string $separator, array $array): string

implode("-", ["a", "b", "c"]);    // "a-b-c"
implode("", ["a", "b", "c"]);     // "abc"
implode(", ", [1, 2, 3]);         // "1, 2, 3" — numbers auto-cast to strings
```

**Legacy note:** the old form `implode($array, $separator)` (reversed) was removed in PHP 8. Always separator first.

## 6. Other string functions you'll use often

```php
strlen("hello")                  // 5
strtolower("HEY")                // "hey"
strtoupper("hey")                // "HEY"
trim("  hi  ")                   // "hi"       — strips leading/trailing whitespace
ltrim("xxhixx", "x")             // "hixx"     — strip from left
rtrim("xxhixx", "x")             // "xxhi"     — strip from right
str_contains("hello", "ell")     // true       (PHP 8+)
str_starts_with("hello", "he")   // true       (PHP 8+)
str_ends_with("hello", "lo")     // true       (PHP 8+)
str_repeat("ab", 3)              // "ababab"
str_pad("7", 3, "0", STR_PAD_LEFT) // "007"
sprintf("%05d", 42)              // "00042"    — printf-style formatting
```

## 7. `strlen` vs multi-byte

`strlen` counts BYTES, not characters. For non-ASCII (emoji, accented chars, Indonesian characters), use `mb_strlen` from the multi-byte extension:

```php
strlen("café")     // 5 — the é is 2 bytes in UTF-8
mb_strlen("café")  // 4 — correct character count
```

Same deal for `substr` → `mb_substr`, `strtolower` → `mb_strtolower`, etc. For this module's exercises ASCII is fine, but remember this exists.

---

## Exercise

Create `exercises/06-strings/slugify.php`.

Write a typed function:

```php
function slugify(string $title): string
```

Given `"Hello, World! PHP & JS 2026"` it should return `"hello-world-php-js-2026"`.

Requirements:
1. Lowercase the input.
2. Replace every non-alphanumeric character (including punctuation, `&`, spaces) with a space. (Easy path: use `str_replace` with an array of known chars — `[",", "!", "&", ".", ":", ";"]`, etc. Stretch path: `preg_replace('/[^a-z0-9]+/', ' ', $s)`.)
3. Use `explode(" ", ...)` to split into words, then `array_filter` to drop empty pieces, then `implode("-", ...)` to rejoin with hyphens.
4. Trim any leading/trailing hyphens with `trim`.

Test with at least:
- `"Hello, World!"` → `"hello-world"`
- `"  Multiple   Spaces   "` → `"multiple-spaces"`
- `"PHP & JS: Type Juggling"` → `"php-js-type-juggling"`

Use `declare(strict_types=1);` at the top.

When done, tell me "review module 6".
