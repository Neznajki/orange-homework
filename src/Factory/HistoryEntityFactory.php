<?php


namespace App\Factory;


use App\Entity\History;

class HistoryEntityFactory
{
    /**
     * @param string $formula
     * @param float $formulaResult
     * @return History
     */
    public static function createEntity(string $formula, float $formulaResult): History
    {
        $result = new History();

        $result->setFormula($formula);
        $result->setResult($formulaResult);

        return $result;
    }
}