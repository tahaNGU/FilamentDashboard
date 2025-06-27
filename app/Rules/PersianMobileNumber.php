<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class PersianMobileNumber implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $pattern = '/^09\d{9}$/';

        if (!preg_match($pattern, $value)) {
            $fail('The :attribute must be a valid number starting with 09 and 11 digits long.');
        }
    }
}
