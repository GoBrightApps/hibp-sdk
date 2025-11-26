<?php

namespace Bright\Hibp\Support;

/**
 * The support class may used some helpers from laravel offical github
 */
class Support
{
    /**
     * The cache of studly-cased words.
     *
     * @var (string)[]
     */
    protected static $studlyCache = [];

    /**
     * Convert a value to studly caps case.
     *
     * @see https://github.com/illuminate/support/blob/master/Str.php#L1624
     *
     * @param  string  $value
     * @return string
     */
    public static function studly($value)
    {
        $key = $value;

        if (isset(static::$studlyCache[$key])) {
            return static::$studlyCache[$key];
        }

        $words = explode(' ', str_replace(['-', '_'], ' ', (string) $value));

        $studlyWords = array_map(fn ($word) => ucfirst($word), $words);

        return static::$studlyCache[$key] = implode($studlyWords);
    }

    /**
     * Join multiple URL segments into a clean URL.
     */
    /**
     * Join multiple URL segments into a clean URL.
     */
    public static function joinUri(string ...$parts): string
    {
        $urls = [];

        foreach ($parts as $i => $part) {
            $part = str_replace('\\', '/', $part);

            if ($i === 0 && preg_match('#^(https?|ftp)://#i', $part, $m)) {
                $protocol = $m[0];
                $rest = substr($part, strlen($protocol));
                $urls[] = $protocol.rtrim((string) preg_replace('#/+#', '/', $rest), '/');
            } else {
                $urls[] = trim((string) preg_replace('#/+#', '/', $part), '/');
            }
        }

        return implode('/', array_filter($urls, fn (string $v): bool => strlen($v) > 0));
    }
}
