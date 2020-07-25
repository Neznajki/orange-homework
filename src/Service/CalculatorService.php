<?php
declare(strict_types=1);

namespace App\Service;


use App\Factory\HistoryEntityFactory;
use App\Formula\GroupFormula;
use App\Repository\HistoryRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use JsonRpcServerBundle\Exception\RpcMessageException;

class CalculatorService
{
    /** @var CalculatorValidatorService */
    protected $validator;
    /** @var HistoryRepository */
    protected $historyRepository;

    /**
     * CalculatorService constructor.
     * @param CalculatorValidatorService $validator
     * @param HistoryRepository $historyRepository
     */
    public function __construct(
        CalculatorValidatorService $validator,
        HistoryRepository $historyRepository
    ) {
        $this->validator = $validator;
        $this->historyRepository = $historyRepository;
    }

    /**
     * @param string $formula
     * @return float
     * @throws ORMException
     * @throws OptimisticLockException
     * @throws RpcMessageException
     */
    public function calculate(string $formula): float
    {
        $formula = $this->cleanFormula($formula);
        $this->validator->validateFormula($formula);

        $groupFormula = new GroupFormula($formula, $this->validator);
        $result = $groupFormula->getValue();

        $this->historyRepository->addResult(
            HistoryEntityFactory::createEntity($formula, $result)
        );

        return $result;
    }

    protected function cleanFormula(string $formula): string
    {
        return preg_replace('/\\s/', '', $formula);
    }
}