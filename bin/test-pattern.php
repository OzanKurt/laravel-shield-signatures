<?php
/**
 * Quick pattern-tester for signature contributions.
 *
 *   php bin/test-pattern.php '/\bgoto\s+\$\w+/' suspicious.php
 *
 * Returns matches with line + column context, or "no match".
 */

if ($argc < 3) {
    fwrite(STDERR, "Usage: php bin/test-pattern.php <regex> <file> [file ...]\n");
    exit(1);
}

$regex = $argv[1];
$files = array_slice($argv, 2);
$totalMatches = 0;

foreach ($files as $file) {
    if (! is_file($file)) {
        fwrite(STDERR, "Skipping missing file: $file\n");
        continue;
    }

    $contents = file_get_contents($file);
    if ($contents === false) continue;

    if (! preg_match_all($regex, $contents, $matches, PREG_OFFSET_CAPTURE)) {
        echo "no match: $file\n";
        continue;
    }

    foreach ($matches[0] as [$match, $offset]) {
        $line = substr_count(substr($contents, 0, $offset), "\n") + 1;
        $column = $offset - strrpos(substr($contents, 0, $offset), "\n");
        $excerpt = substr($contents, max(0, $offset - 20), strlen($match) + 40);
        $excerpt = str_replace(["\n", "\r"], ' ', $excerpt);
        echo "{$file}:{$line}:{$column} | {$excerpt}\n";
        $totalMatches++;
    }
}

echo "\nTotal matches: {$totalMatches}\n";
exit($totalMatches > 0 ? 0 : 1);
