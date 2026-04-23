# Module 8 — Capstone

> Goal: one script that demonstrates every acceptance criterion in a realistic scenario. This is the file you'll show your senior.

## The scenario

You're building a small text-report generator for an e-commerce team. Given a list of transactions, your script should:

1. Drop refunded transactions.
2. Normalize each transaction's category into a slug.
3. Aggregate total revenue per category.
4. Print a tidy text report.

No database, no frameworks. One file, CLI-runnable.

## Input data (hardcode this at the top)

```php
$transactions = [
    ['id' => 1, 'user' => 'alice',   'amount' => 42.50,  'category' => 'Books & Media',     'ts' => '2026-04-18 10:15:00', 'refunded' => false],
    ['id' => 2, 'user' => 'bob',     'amount' => 19.99,  'category' => 'Home Goods',        'ts' => '2026-04-18 11:40:00', 'refunded' => true],
    ['id' => 3, 'user' => 'alice',   'amount' => 120.00, 'category' => 'Electronics',       'ts' => '2026-04-19 09:00:00', 'refunded' => false],
    ['id' => 4, 'user' => 'carol',   'amount' => 8.00,   'category' => 'Books & Media',     'ts' => '2026-04-19 12:30:00', 'refunded' => false],
    ['id' => 5, 'user' => 'dave',    'amount' => 250.00, 'category' => 'Electronics',       'ts' => '2026-04-20 14:00:00', 'refunded' => false],
    ['id' => 6, 'user' => 'bob',     'amount' => 15.00,  'category' => 'Home Goods',        'ts' => '2026-04-20 16:45:00', 'refunded' => false],
    ['id' => 7, 'user' => 'erin',    'amount' => 99.00,  'category' => 'Electronics',       'ts' => '2026-04-21 08:20:00', 'refunded' => true],
    ['id' => 8, 'user' => 'frank',   'amount' => 5.50,   'category' => 'Books & Media',     'ts' => '2026-04-21 19:10:00', 'refunded' => false],
];
```

## Required checklist (map to acceptance criteria)

| # | AC | How the capstone hits it |
|---|---|---|
| 1 | Loops / conditions / functions | `foreach` over filtered results, `match` inside a helper, multiple functions |
| 2 | Type juggling vs `strict_types` | `declare(strict_types=1);` at the top; every helper has typed params + return |
| 3 | Associative arrays | Every transaction is an assoc array; the aggregate result is an assoc array |
| 4 | `array_map` / `array_filter` / `array_reduce` | One of each, minimum |
| 5 | `substr` / `str_replace` / `explode` / `implode` | All four used at least once |
| 6 | Typed reusable functions with return types | At least 4 helper functions, all typed |

## Expected output (roughly)

```
=== Daily Revenue Report ===
Period: 2026-04-18 to 2026-04-21

Completed transactions : 6
Refunded transactions  : 2

Revenue by category:
  - books-media ........ $ 56.00
  - electronics ........ $370.00
  - home-goods ......... $ 15.00

Total revenue: $441.00
```

(Exact formatting is up to you — dots are padding, alignment via `str_pad`.)

## Functions you'll likely want

```php
function slugify(string $input): string { ... }                 // Module 6 deliverable — reuse!
function dateOnly(string $timestamp): string { ... }            // explode on " ", return [0]
function isCompleted(array $tx): bool { ... }                   // for array_filter
function totalsByCategory(array $txs): array { ... }            // uses array_reduce
function formatReport(array $totals, int $completed, int $refunded, string $from, string $to): string { ... }
```

## Hints

- `dateOnly("2026-04-18 10:15:00")` → `"2026-04-18"`. Use `explode(" ", $ts)[0]`. Or `substr($ts, 0, 10)`.
- For the "Period" line, find min and max dates across the filtered transactions — `array_map` then `min()` / `max()`.
- For dot-padded category lines, `str_pad($label, 20, '.', STR_PAD_RIGHT)` is convenient.
- For dollar formatting, `number_format($amount, 2)` gives `"441.00"`.
- Print the final report using `echo` + `implode(PHP_EOL, $lines)`.

## Anti-patterns to avoid

- Don't use `==` anywhere. `===` everywhere.
- Don't mutate `$transactions`. Treat it as immutable input; build new arrays.
- No classes needed — keep it functional.
- Don't call `slugify` inside a hot loop manually when `array_map` does it cleanly.

---

## Exercise

Create `exercises/08-capstone/report.php`. Hit every row in the checklist. Run it with:

```bash
php exercises/08-capstone/report.php
```

When done, tell me "review capstone" and I'll do a full code review mapping your file back to each acceptance criterion — which is what your senior will effectively be doing.
