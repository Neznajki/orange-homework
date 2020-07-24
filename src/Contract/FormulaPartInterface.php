<?php


namespace App\Contract;


interface FormulaPartInterface
{
    /** method that will calculate result */
    public function getValue(): float;
}