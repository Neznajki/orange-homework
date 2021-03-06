<?php


namespace App\Service;


use JsonRpcServerBundle\Exception\RpcMessageException;

class CalculatorValidatorService
{
    public function validateFormula(string $formula): void
    {
        if (
            ! preg_match("@^[0-9-.+/*()]+$@", $formula)
        ) {
            throw new RpcMessageException("unsupported symbols in formula");
        }

        if (strlen($formula) > 128) {
            throw new RpcMessageException('max formula length is 128');
        }

        if (
            $this->isDoubleSigned($formula) ||
            preg_match('@^[*/.]@', $formula) ||
            preg_match('@[-+*/.]$@', $formula) ||
            preg_match('@[0-9]+\\.[0-9]+\\.@', $formula)
        ) {
            throw new RpcMessageException('invalid formula detected');
        }
    }

    /**
     * @param string $formula
     * @return bool
     */
    public function isDoubleSigned(string $formula): bool
    {
        return
            preg_match('@[-+*/.][-+*/.]@', $formula) &&
            ! preg_match('@[*/][-+]@', $formula);
    }
}