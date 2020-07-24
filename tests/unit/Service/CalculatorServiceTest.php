<?php

namespace App\Tests\Service\CalculatorService;

use App\Repository\HistoryRepository;
use App\Service\CalculatorService;
use App\Service\CalculatorValidatorService;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractSimpleCodeceptionTest;

class CalculatorServiceTest extends AbstractSimpleCodeceptionTest
{

    /**
     * @dataProvider getData_testCalculate
     * @param string $formula
     * @param float $expectingResult
     * @throws \Exception
     */
    public function testCalculate(string $formula, float $expectingResult)
    {
        /** @var MockObject|HistoryRepository $historyRepository */
        $historyRepository = $this->createMockExpectsOnlyMethodUsage(
            HistoryRepository::class,
            [
                'addResult'
            ]
        );

        $class = new CalculatorService(new CalculatorValidatorService(),$historyRepository);

        $result = $class->calculate($formula);

        $this->assertEquals(number_format($expectingResult, 2), number_format($result, 2), "formula check with precision 2 decimals");
    }

    public function getData_testCalculate(): array
    {
        return [
            ['formula' => '20+44*(2*4+30+(55-33)+1*2)+(2+2)', 'expectingResult' => 20+44*(2*4+30+(55-33)+1*2)+(2+2)],
            ['formula' => '20+44*(2*4+30+(55-33)+1*2)+(2+2/3)', 'expectingResult' => 20+44*(2*4+30+(55-33)+1*2)+(2+2/3)],
            ['formula' => '20+44*(2*4+30+(55-33)+1*2-44)+(2+2/3)', 'expectingResult' => 20+44*(2*4+30+(55-33)+1*2-44)+(2+2/3)],
            ['formula' => '2+2/3', 'expectingResult' => 2+2/3],
            ['formula' => '(2+2)/5', 'expectingResult' => (2+2)/5],
            ['formula' => '(2+2 * 2)/5', 'expectingResult' => (2+2*2)/5],
        ];
    }

    protected function getWorkingClassName(): string
    {
        return CalculatorService::class;
    }
}
