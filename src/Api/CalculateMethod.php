<?php


namespace App\Api;


use App\Service\CalculatorService;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonRpcServerBundle\Contract\MethodHandlerInterface;
use JsonRpcServerBundle\Exception\RpcMessageException;

class CalculateMethod implements MethodHandlerInterface
{
    /** @var CalculatorService */
    protected $calculator;

    /** @var string */
    protected $formula;

    /**
     * CalculateMethod constructor.
     * @param CalculatorService $calculator
     */
    public function __construct(CalculatorService $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * @return mixed|string
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws RpcMessageException
     */
    public function handle()
    {
        return $this->calculator->calculate($this->formula);
        //not implemented
    }

    public function getMethod(): string
    {
        return "calculate";
    }

    public function getRequiredParameters(): array
    {
        return ['formula'];
    }

    public function setParameter(string $paramName, $arg): void
    {
        $this->$paramName = $arg;
    }
}