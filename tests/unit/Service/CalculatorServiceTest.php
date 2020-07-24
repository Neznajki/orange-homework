<?php /** @noinspection PhpIllegalPsrClassPathInspection */

namespace App\Tests\Service\CalculatorService;

use App\Entity\History;
use App\Factory\HistoryEntityFactory;
use App\Repository\HistoryRepository;
use App\Service\CalculatorService;
use App\Service\CalculatorValidatorService;
use AspectMock\Test;
use Exception;
use JsonRpcServerBundle\Exception\RpcMessageException;
use LogicException;
use PHPUnit\Framework\MockObject\MockObject;
use Tests\Neznajka\Codeception\Engine\Abstraction\AbstractSimpleCodeceptionTest;

/**
 * Class CalculatorServiceTest
 * @package App\Tests\Service\CalculatorService
 * @method CalculatorService|MockObject getWorkingClass(...$mockedMethods)
 */
class CalculatorServiceTest extends AbstractSimpleCodeceptionTest
{

    /**
     * @dataProvider getData_testCalculate
     * @param string $formula
     * @param float $expectingResult
     * @throws Exception
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
        $mock = $this->createMockExpectsNoUsage(History::class);

        $function = function($f, $r) use ($mock, $formula, $expectingResult){
            if (preg_replace('/\\s/','',$formula) != $f) {
                throw new LogicException("formula does not match");
            }

            if (number_format($r, 2) != number_format($expectingResult, 2)) {
                throw new LogicException("formula does not match");
            }

            return $mock;
        };

        Test::double(HistoryEntityFactory::class, ['createEntity' => $function]);

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
            ['formula' => '2+   2/3', 'expectingResult' => 2+2/3],
            ['formula' => '(2+2)/5', 'expectingResult' => (2+2)/5],
            ['formula' => '(2+2 * 2)/5', 'expectingResult' => (2+2*2)/5],
        ];
    }

    /**
     * @dataProvider getData_testCalculateErrors
     * @param string $formula
     * @param string $errorMessage
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     * @throws \JsonRpcServerBundle\Exception\RpcMessageException
     * @throws \ReflectionException
     */
    public function testCalculate_case_errors(string $formula, string $errorMessage)
    {
        $workingClass = $this->getWorkingClass("cleanFormula");

        $this->setNotPublicValue($workingClass, 'validator', new CalculatorValidatorService());
        $this->expectExceptionObject(new RpcMessageException($errorMessage));

        $workingClass->expects($this->once())->method('cleanFormula')->with($formula)->willReturn(preg_replace('/\\s/','',$formula));

        $workingClass->calculate($formula);
    }

    public function getData_testCalculateErrors(): array
    {
        return [
            ['formula' => '20+44*(2*4+30+(55-33)f+1*2)+(2+2)', 'errorMessage' => 'unsupported symbols in formula'],
            ['formula' => '20+44*(2*4+30+(55-3$3)+1*2)+(2+2)', 'errorMessage' => 'unsupported symbols in formula'],
            ['formula' => '20+44*(2*4+30+(55-33f1)+1*2)+(2+2)', 'errorMessage' => 'unsupported symbols in formula'],
            ['formula' => '20+44*(2*4+30+(55^331)+1*2)+(2+2)', 'errorMessage' => 'unsupported symbols in formula'],
            ['formula' => '20+44*(2*4+30+(55-33)+1*2)+(2+2)+ 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2)+ 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2)+ 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2) + 20+44*(2*4+30+(55-33)+1*2)+(2+2)',
                'errorMessage' => 'max formula length is 128'],
            ['formula' => '20++44', 'errorMessage' => 'invalid formula detected'],
            ['formula' => '20+11+', 'errorMessage' => 'invalid formula detected'],
            ['formula' => '20+-11+', 'errorMessage' => 'invalid formula detected'],
            ['formula' => '-20-11+', 'errorMessage' => 'invalid formula detected'],
            ['formula' => '2.0-1..1+', 'errorMessage' => 'invalid formula detected'],
            ['formula' => '2.0-1.3.1+', 'errorMessage' => 'invalid formula detected'],
        ];
    }

    protected function getWorkingClassName(): string
    {
        return CalculatorService::class;
    }
}
