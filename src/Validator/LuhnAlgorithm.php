<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class LuhnAlgorithm extends Constraint
{
    public $message = 'Validation for {{ string }} was failed. Luhn algorithm wasn\'t passed';
}
