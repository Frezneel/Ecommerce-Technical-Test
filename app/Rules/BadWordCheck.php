<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class BadWordCheck implements ValidationRule
{
    /**
     * Run the validation rule.
     *
     * @param  Closure(string, ?string=): PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (stripos($value, 'bajakan') !== false) {
            $fail("Barang bajakan tidak boleh dijual di sistem ini.");
        }
        if (stripos($value, 'palsu') !== false) {
            $fail("Barang palsu tidak boleh dijual di sistem ini.");
        }
        if (stripos($value, 'kw') !== false) {
            $fail("Barang kw tidak boleh dijual di sistem ini.");
        }
    }
}
