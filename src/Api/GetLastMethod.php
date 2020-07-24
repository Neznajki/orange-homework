<?php


namespace App\Api;


use App\Repository\HistoryRepository;
use JsonRpcServerBundle\Contract\MethodHandlerInterface;
use JsonRpcServerBundle\Exception\InvalidParamsException;

class GetLastMethod implements MethodHandlerInterface
{
    /** @var int */
    protected $limit = 5;
    /**
     * @var HistoryRepository
     */
    private $historyRepository;

    /**
     * GetLastMethod constructor.
     * @param HistoryRepository $historyRepository
     */
    public function __construct(HistoryRepository $historyRepository)
    {
        $this->historyRepository = $historyRepository;
    }

    /**
     * @return array
     */
    public function handle(): array
    {
        $result = [];

        foreach ($this->historyRepository->getLastRecords($this->limit) as $entity) {
            $result[] = ['formula' => $entity->getFormula(), 'result' => $entity->getResult()];
        }

        return $result;
    }

    public function setParameter(string $paramName, $value): void
    {
        switch ($paramName) {
            case 'limit':
                if (! is_numeric($value)) {
                    throw new InvalidParamsException("{$paramName} should be string");
                }
                $this->limit = $value;
                break;
            default:
                throw new InvalidParamsException("parameter {$paramName} is not supported");
        }
    }

    public function getMethod(): string
    {
        return 'getLatest';
    }

    public function getRequiredParameters(): array
    {
        return [];
    }
}