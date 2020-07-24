<?php


namespace App\Formula;


use JsonRpcServerBundle\Exception\RpcMessageException;

class Calculator
{
    private $a;
    private $op;
    private $b;

    /**
     * Calculator constructor.
     * @param $a
     * @param $op
     * @param $b
     */
    public function __construct($a, $op, $b)
    {
        $this->a = $a;
        $this->op = $op;
        $this->b = $b;
    }

    /**
     * @return float
     * @throws RpcMessageException
     */
    public function calculate(): float
    {
        switch ($this->op) {
            case '-':
                $result = $this->a - $this->b;
                break;
            case '+':
                $result = $this->a + $this->b;
                break;
            case '*':
                $result = $this->a * $this->b;
                break;
            case '/':
                $result = $this->a / $this->b;
                break;
            default:
                throw new RpcMessageException("unsupported operation {$this->op}");
        }

        return $result;
    }
}