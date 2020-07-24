<?php

namespace App\Entity;

use DateTime;
use Doctrine\ORM\Mapping as ORM;

/**
 * History
 *
 * @ORM\Table(name="history")
 * @ORM\Entity
 */
class History
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="formula", type="string", length=128, nullable=false)
     */
    private $formula;

    /**
     * @var float
     *
     * @ORM\Column(name="result", type="float", precision=10, scale=0, nullable=false)
     */
    private $result;

    /**
     * @var DateTime
     *
     * @ORM\Column(name="executed", type="datetime", nullable=false, options={"default"="CURRENT_TIMESTAMP"})
     */
    private $executed = 'CURRENT_TIMESTAMP';

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getFormula(): string
    {
        return $this->formula;
    }

    /**
     * @param string $formula
     */
    public function setFormula(string $formula): void
    {
        $this->formula = $formula;
    }

    /**
     * @return float
     */
    public function getResult(): float
    {
        return $this->result;
    }

    /**
     * @param float $result
     */
    public function setResult(float $result): void
    {
        $this->result = $result;
    }

    /**
     * @return DateTime
     */
    public function getExecuted(): DateTime
    {
        return $this->executed;
    }
}
