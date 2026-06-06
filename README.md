# Laravel Shield Signatures

> [!WARNING]
> **DEPRECATED.** Signature distribution has moved into the Laravel Shield Central app
> (`laravel-shield-app`). The plugin's `shield:signatures-sync` now pulls from the
> Central API, which gates the free/premium channels server-side by license:
>
> - **Free** (public, lagged ~30 days): `GET https://laravel-shield.ozankurt.com/api/signatures/free`
> - **Premium** (license bearer, fresh): `GET https://laravel-shield.ozankurt.com/api/signatures/premium`
>
> The `signatures.json` bundle here was migrated into the app's `feed_signatures`
> table (`database/data/signatures.json` + `SignatureSeeder`). New signatures are
> curated there. This repo is kept for history and can be archived.

---

Versioned malware/webshell signature feed for [Laravel Shield](https://github.com/OzanKurt/laravel-shield).

The Shield plugin's `shield:signatures-sync` Artisan command pulls the **latest tagged release** of this repo and upserts its `signatures.json` into the application's `ls_signatures` table.

## How releases work

Every release is a tag (e.g. `v2026.05.01`) with a `signatures.json` asset attached. The plugin fetches:

```
GET https://api.github.com/repos/OzanKurt/laravel-shield-signatures/releases/latest
```

then downloads the `signatures.json` asset and verifies a SHA-256 checksum included in the release notes.

Buyers can pin a specific tag via `LS_SIGNATURE_PIN=v2026.05.01` in their `.env`.

## Signature format

```jsonc
[
  {
    "ref": "category.specific_name",
    "name": "Short human label",
    "category": "backdoor",
    "kind": "regex",
    "pattern": "/.../i",
    "severity": "critical",
    "version": 1,
    "is_enabled": true
  }
]
```

See CONTRIBUTING.md for the full spec + contribution guide.

## Releasing a new bundle

```bash
./bin/build.sh                              # combines per-category files
sha256sum signatures.json > signatures.json.sha256
git tag -a v2026.05.01 -m "..."
git push origin v2026.05.01
gh release create v2026.05.01 signatures.json signatures.json.sha256
```

## License

MIT — see LICENSE.md.

Signature patterns are facts/data and not subject to copyright. Tooling is MIT.

Many patterns derived from [Wordfence Security](https://www.wordfence.com/) (MIT) — see the `meta.wordfence_derived` field per entry.
