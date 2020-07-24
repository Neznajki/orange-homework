<?php


namespace App\Formula;


use App\Contract\FormulaPartInterface;
use JsonRpcServerBundle\Exception\RpcMessageException;
use LogicException;

class GroupFormula implements FormulaPartInterface
{
    /** @var string */
    protected $rawFormula;
    /** @var string */
    protected $formula;

    /**
     * GroupFormula constructor.
     * @param string $formula
     */
    public function __construct(string $formula)
    {
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

        if (preg_match_all('@\(([^()]+)\)@', $formula, $matches)) {
            foreach ($matches[1] as $pos => $value) {
                $tmp = new GroupFormula($value);
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
        $regex = sprintf('@([0-9.]+)([%s]{1})([0-9.]+)@', $symbols);
        while (preg_match_all($regex, $this->formula, $matches)) {
            $i ++;

            if ($i > $maxIterations) {
                throw new LogicException("max iterations reached");
            }

            foreach ($matches[0] as $index => $value) {
                $calculator = new Calculator($matches[1][$index], $matches[2][$index], $matches[3][$index]);
                $this->formula = str_replace($value, $calculator->calculate(), $this->formula);
            }
        }
    }
}