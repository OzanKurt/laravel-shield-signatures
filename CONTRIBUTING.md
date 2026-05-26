# Contributing signatures

Thanks for helping make Laravel Shield's malware detection broader and more accurate.

## Before submitting

1. **No false positives.** Test your pattern against:
   - The Laravel framework source (`vendor/laravel/framework`)
   - Symfony components
   - Doctrine ORM
   - Spatie packages
   - Common Laravel apps you maintain
2. **Be specific.** `eval` alone is too generic. `eval\s*\(\s*base64_decode\s*\(` is specific enough.
3. **Document the threat.** In the PR description, explain what real-world malware/webshell this pattern catches, with a sample (sanitized) snippet.
4. **Pick the right category:**
   - `backdoor` — code that opens remote access (eval/assert/exec on user input)
   - `webshell` — known webshell families (c99, r57, WSO, b374k, etc.)
   - `phishing` — credential harvesters, fake login form senders
   - `heuristic` — soft signals (long base64 strings, suspicious file ops)
   - `malware` — file-hash matches against known-bad binaries
5. **Stable refs.** Once published in a release, a `ref` belongs to a specific pattern forever. To change the pattern, bump `version`. To replace the pattern entirely (different intent), use a new `ref`.

## Format

```jsonc
{
  "ref": "category.specific_name",
  "name": "Short human label",
  "description": "Optional longer explanation",
  "category": "backdoor",
  "kind": "regex",
  "pattern": "/.../",
  "severity": "critical",
  "version": 1,
  "is_enabled": true,
  "meta": {
    "wordfence_derived": true,
    "first_seen": "2026-05-26"
  }
}
```

## Testing

A simple PHP test harness lives in `bin/test-pattern.php`:

```bash
php bin/test-pattern.php "/\beval\s*\(\s*base64_decode\s*\(/i" path/to/sample.php
```

Returns matched offsets or "no match".

## Release cadence

We aim for monthly tagged releases. Critical signatures (active campaigns) ship out-of-band as patch releases.

## License

By submitting a PR, you agree your contribution can be redistributed under the repo's terms (MIT for the tooling; signatures themselves are facts/data and not subject to copyright).
