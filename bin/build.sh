#!/usr/bin/env bash
# Build signatures.json from per-category files under signatures/.
# Usage: bin/build.sh
set -euo pipefail

OUT=signatures.json
TMP=signatures.tmp.json

if [ -d signatures ]; then
    # Combine all JSON arrays under signatures/ into one flat array
    jq -s 'add' signatures/*.json > "$TMP"
    mv "$TMP" "$OUT"
fi

# Compute checksum
sha256sum "$OUT" > "${OUT}.sha256"

echo "Built ${OUT} ($(wc -c < $OUT) bytes, $(jq '. | length' $OUT) signatures)"
echo "Checksum: $(cat ${OUT}.sha256)"
