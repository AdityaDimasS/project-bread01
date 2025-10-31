<?php

namespace App\Services;

use Illuminate\Support\Facades\Crypt;

class Encryptor
{
    // Caesar cipher sederhana
    private static function caesarEncrypt(string $text, int $shift = 3): string
    {
        $result = '';
        foreach (str_split($text) as $char) {
            $ascii = ord($char);
            if ($ascii >= 32 && $ascii <= 126) {
                $result .= chr(32 + (($ascii - 32 + $shift) % 95));
            } else {
                $result .= $char;
            }
        }
        return $result;
    }

    private static function caesarDecrypt(string $text, int $shift = 3): string
    {
        return self::caesarEncrypt($text, 95 - $shift);
    }

    // Skytale cipher sederhana
    private static function skytaleEncrypt(string $text, int $numRows = 3): string
    {
        $result = '';
        $len = strlen($text);
        for ($i = 0; $i < $numRows; $i++) {
            for ($j = $i; $j < $len; $j += $numRows) {
                $result .= $text[$j];
            }
        }
        return $result;
    }

    private static function skytaleDecrypt(string $text, int $numRows = 3): string
    {
        $len = strlen($text);
        $numCols = ceil($len / $numRows);
        $matrix = array_fill(0, $numRows, '');
        $index = 0;

        for ($i = 0; $i < $numRows; $i++) {
            for ($j = 0; $j < $numCols; $j++) {
                if ($index < $len) {
                    $matrix[$i] .= $text[$index++];
                }
            }
        }

        $result = '';
        for ($j = 0; $j < $numCols; $j++) {
            for ($i = 0; $i < $numRows; $i++) {
                if (isset($matrix[$i][$j])) {
                    $result .= $matrix[$i][$j];
                }
            }
        }
        return $result;
    }

    // Kombinasi Caesar → Skytale → AES
    public static function encryptPassword(string $password): string
    {
        $caesar = self::caesarEncrypt($password);
        $skytale = self::skytaleEncrypt($caesar);
        return Crypt::encryptString($skytale); // AES bawaan Laravel
    }

    // Kombinasi AES → Skytale → Caesar
    public static function decryptPassword(string $encrypted): string
    {
        $aes = Crypt::decryptString($encrypted);
        $skytale = self::skytaleDecrypt($aes);
        return self::caesarDecrypt($skytale);
    }
}
