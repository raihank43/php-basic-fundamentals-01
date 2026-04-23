# Module 9 — Journey Documentation

> Goal: hand your senior a `JOURNEY.md` that proves you actually learned this — not just that you copy-pasted code that runs.

## What goes in `JOURNEY.md`

Use this skeleton. Fill it in honestly — including the parts where you got stuck. A learning doc that admits confusion is more useful than one that pretends everything was smooth.

```markdown
# PHP Basic Fundamentals 01 — My Journey

## 1. Overview
- What I was asked to learn (paste the acceptance criteria).
- How I structured the work (modules 1–8 + capstone).
- Time spent (rough hours per module is fine).

## 2. What I learned (module by module)
For each of the 8 modules, write 3–5 sentences in your own words covering:
- The concept
- One specific thing that surprised me
- How it differs from JS/React

(Don't copy from materials/. Restate it like you're explaining to a JS friend.)

## 3. JS → PHP "aha" moments
Bullet list. Examples to get you started:
- "PHP arrays are ordered maps — I kept reaching for `Object` mentally and it bit me until I stopped."
- "Closures don't auto-capture — `use ($var)` is mandatory in `function() {}` form."
- "`array_map` takes the callback first, `array_filter` takes the array first. There is no logic, just memorize."
- "`'0'` is falsy in PHP. JS would say `Boolean('0') === true`."

## 4. Struggles
Pull from STRUGGLES.log. For each one, write:
- What I tried
- Why it didn't work (the actual reason, after I figured it out)
- The fix

If a struggle had no clean fix, that's still a valid entry — say what's still murky.

## 5. Open questions for my senior
List anything you're unsure about. Examples:
- "When should I reach for classes vs plain functions in PHP?"
- "Is there a project-wide convention for `strict_types` or do I add it per file?"
- "How does the team handle dependency injection without a framework?"

## 6. Capstone walkthrough
Annotated explanation of `exercises/08-capstone/report.php`.
- For each function, one sentence on what it does and which AC it ticks.
- Sample output of the script.
- One thing I'd improve if I rewrote it now.

## 7. Acceptance criteria checklist
Re-list the 6 ACs, with a link to the file/line that proves you covered each one.
```

## How to use STRUGGLES.log along the way

Don't write the journey at the end from memory — you'll forget the confusing bits because they'll feel obvious by then. Instead, every time you hit a wall, append a one-liner to `STRUGGLES.log`:

```
[Module 2] Thought "0" was truthy because JS Boolean("0") is true. var_dump((bool)"0") returned false — PHP treats "0" as falsy.
[Module 5] Spent 10 min wondering why my filtered array's keys were [1,3,5] instead of [0,1,2]. Wrapped with array_values() to reindex.
[Module 7] Forgot declare(strict_types=1) had to be the LITERAL first statement. Putting a comment block above it: fine. Putting any code above it: silently disables strict mode for the file.
```

When you write `JOURNEY.md` at the end, sections 3 and 4 nearly write themselves.

---

## Exercise

After modules 1–8 are done and reviewed:

1. Skim `STRUGGLES.log` and your code in `exercises/`.
2. Draft `JOURNEY.md` at the project root using the skeleton above.
3. Tell me "review journey" and I'll check it for honesty (no glossing over confusing parts), completeness (all 6 ACs covered with file/line references), and clarity (a senior should be able to read it in under 10 minutes).

That's the deliverable bundle: `ROADMAP.md` + `materials/` + `exercises/` + `JOURNEY.md`.
