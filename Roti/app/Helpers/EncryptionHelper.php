<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Crypt;

class EncryptionHelper
{
    // === Caesar Cipher (huruf + angka) ===
    public static function caesarEncrypt(string $text, int $shift = 3): string
    {
        $result = '';
        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                // Geser huruf A-Z atau a-z
                $base = ctype_upper($char) ? 'A' : 'a';
                $result .= chr(((ord($char) - ord($base) + $shift) % 26) + ord($base));
            } elseif (ctype_digit($char)) {
                // Geser angka 0–9
                $result .= chr(((ord($char) - ord('0') + $shift) % 10) + ord('0'));
            } else {
                // Karakter lain tidak digeser
                $result .= $char;
            }
        }
        return $result;
    }

    public static function caesarDecrypt(string $text, int $shift = 3): string
    {
        // Untuk huruf (26 huruf), untuk angka (10 angka)
        $result = '';
        foreach (str_split($text) as $char) {
            if (ctype_alpha($char)) {
                $base = ctype_upper($char) ? 'A' : 'a';
                $result .= chr(((ord($char) - ord($base) - $shift + 26) % 26) + ord($base));
            } elseif (ctype_digit($char)) {
                $result .= chr(((ord($char) - ord('0') - $shift + 10) % 10) + ord('0'));
            } else {
                $result .= $char;
            }
        }
        return $result;
    }

    // === Skytale Cipher ===
    public static function skytaleEncrypt(string $text, int $key = 3): string
    {
        $result = '';
        $len = strlen(string: $text);
        for ($i = 0; $i < $key; $i++) {
            for ($j = $i; $j < $len; $j += $key) {
                $result .= $text[$j];
            }
        }
        return $result;
    }

    public static function skytaleDecrypt(string $text, int $key = 3): string
    {
        $len = strlen($text);
        $rows = ceil($len / $key);
        $result = str_repeat(' ', $len);
        $pos = 0;
        for ($i = 0; $i < $key; $i++) {
            for ($j = $i; $j < $len; $j += $key) {
                $result[$j] = $text[$pos++] ?? ' ';
            }
        }
        return $result;
    }

    // === Kombinasi Caesar + Skytale + AES ===
    public static function encryptHybrid(string $text): string
    {
        $caesar = self::caesarEncrypt($text, 5);  // geser huruf dan angka 5 langkah
        $skytale = self::skytaleEncrypt($caesar, 4);
        return Crypt::encryptString($skytale); // AES bawaan Laravel
    }

    public static function decryptHybrid(string $cipher): string
    {
        $aes = Crypt::decryptString($cipher);
        $skytale = self::skytaleDecrypt($aes, 4);
        return self::caesarDecrypt($skytale, 5);
    }
}
