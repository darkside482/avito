<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

/**
 * Class LuhnAlgorithmValidator
 * @package App\Validator
 */
class LuhnAlgorithmValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof LuhnAlgorithm) {
            throw new UnexpectedTypeException($constraint, LuhnAlgorithm::class);
        }

        if ($value === null || $value === '') {
            return;
        }

        if (!is_string($value)) {
            throw new UnexpectedValueException($value, 'string');
        }

        if (!$this->luhnAlgorithm($value))
        {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ string }}', $value)
                ->addViolation();
        }
    }

    private function luhnAlgorithm(string $value): bool
    {
        $number = strrev(preg_replace('/[^\d]+/', '', $value));
        $sum = 0;
        for ($i = 0, $j = strlen($number); $i < $j; $i++) {
            if (($i % 2) == 0) {
                $val = $number[$i];
            } else {
                $val = $number[$i] * 2;
                if ($val > 9)  {
                    $val -= 9;
                }
            }
            $sum += $val;
        }
        $result = (($sum % 10) === 0);
        return (($sum % 10) === 0);
    }
}
