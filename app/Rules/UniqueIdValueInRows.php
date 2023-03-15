<?php 
namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Closure;

class UniqueIdValueInRows implements ValidationRule
{
    /**
     * The unique IDs found so far.
     *
     * @var array
     */
    protected $uniqueIds = [];

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if(in_array($value, $this->uniqueIds)) {
            $fail('The :attribute value must be unique in payload');
        }
        $this->uniqueIds[] = $value;
    }
}
