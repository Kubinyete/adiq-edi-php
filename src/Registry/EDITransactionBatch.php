<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDITransactionBatch extends EDIRegistry
{
    public const CURRENCY_REAL = 'RE';
    public const CURRENCY_DOLAR = 'DO';
    public const CURRENCY_PESO = 'PE';

    #[Text(2)]
    public string $registryCode;

    #[Date(8, format: '!Ymd')]
    public DateTimeInterface $movementDate;
    #[Text(2)]
    public string $currencyCode;

    #[Number(6)]
    public int $registryNseq;
}
