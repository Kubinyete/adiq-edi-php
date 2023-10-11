<?php

namespace Kubinyete\Edi\Adiq\Document;

use Closure;
use DateTimeInterface;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatch;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatchEnd;

class Envelope
{
    public DateTimeInterface $date;
    public string $currencyCode;

    public int $registryTotalCount;
    public string $registryTotalCreditAmount;

    public function __construct(EDITransactionBatch $header, EDITransactionBatchEnd $tail, public int $lineStart, public int $lineEnd, private Document $document)
    {
        $this->date = $header->movementDate;
        $this->currencyCode = $header->currencyCode;

        $this->registryTotalCount = $tail->registryTotalCount;
        $this->registryTotalCreditAmount = $tail->registryTotalCreditAmount;
    }

    public function getEntries(): iterable
    {
        yield from $this->document->eachItemFromLine($this->lineStart, $this->lineEnd);
    }

    public function each(Closure $closure): void
    {
        foreach ($this->getEntries() as $entry) {
            $closure->__invoke($entry);
        }
    }
}
