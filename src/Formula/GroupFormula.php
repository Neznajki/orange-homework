<?php


namespace App\Formula;


use App\Contract\FormulaPartInterface;
use App\Service\CalculatorValidatorService;
use JsonRpcServerBundle\Exception\RpcMessageException;
use LogicException;

class GroupFormula implements FormulaPartInterface
{
    /** @var string */
    protected $rawFormula;
    /** @var string */
    protected $formula;
    /** @var CalculatorValidatorService */
    protected $validatorService;

    /**
     * GroupFormula constructor.
     * @param string $formula
     * @param CalculatorValidatorService $validatorService
     * @throws RpcMessageException
     */
    public function __construct(string $formula, CalculatorValidatorService $validatorService)
    {
        $this->validatorService = $validatorService;
        $this->rawFormula = $formula;
        $this->formula = $this->calcSubGroups($formula);
    }

    /**
     * @return float
     * @throws RpcMessageException
     */
    public function getValue(): float
    {
        $this->calcAlg("*/");
        $this->calcAlg("+-");

        return floatval($this->formula);
    }

    /**
     * @param string $formula
     * @return string
     * @throws RpcMessageException
     */
    protected function calcSubGroups(string $formula): string
    {
        $result = $formula;

        $this->validatorService->validateFormula($formula);

        if (preg_match_all('@\(([^()]+)\)@', $result, $matches)) {
            foreach ($matches[1] as $pos => $value) {
                $tmp = new GroupFormula($value, $this->validatorService);
                $result = str_replace($matches[0][$pos], $tmp->getValue(), $result);
            }

            $result = $this->calcSubGroups($result);
        }

        return $result;
    }

    /**
     * @param string $symbols
     * @return void
     * @throws RpcMessageException
     */
    public function calcAlg(string $symbols): void
    {
        $i = 0;
        $maxIterations = 100;
        $regexSecondPart = "([{$symbols}]{1})([+-]?[0-9.]+)";
        $regex = "@([0-9.]+){$regexSecondPart}@";

        $negativeRegex = "@^(-[0-9.]+){$regexSecondPart}@";
        if (preg_match_all($negativeRegex, $this->formula, $matches)) {
            $this->calculateSingleMatch($matches);
        }

        while (preg_match_all($regex, $this->formula, $matches)) {
            $i ++;
            if ($i > $maxIterations) {
                throw new LogicException("max iterations reached");
            }

            $this->calculateSingleMatch($matches);
        }
    }

    /**
     * @param $matches
     * @throws RpcMessageException
     */
    public function calculateSingleMatch($matches): void
    {
        foreach ($matches[0] as $index => $value) {
            $calculator = new Calculator($matches[1][$index], $matches[2][$index], $matches[3][$index]);
            $this->formula = str_replace($value, $calculator->calculate(), $this->formula);
            $this->formula = str_replace('+-', '-', $this->formula);
        }
    }
}