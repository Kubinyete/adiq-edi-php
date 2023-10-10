<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Registry\Field\Numeric;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDICancelReceipt extends EDIRegistry
{
    public const CAPTURE_TYPE_MANUAL = '1';
    public const CAPTURE_TYPE_POS = '2';
    public const CAPTURE_TYPE_PDV = '3';
    public const CAPTURE_TYPE_TRANSACTION_OFF = '4';
    public const CAPTURE_TYPE_INTERNET = '5';
    public const CAPTURE_TYPE_URA = '6';
    public const CAPTURE_TYPE_UNDEFINED = '8';
    public const CAPTURE_TYPE_OTHERS = '9';

    #[Text(2)]
    public string $registryCode;
    #[Numeric(15)]
    public string $establishmentCode;

    #[Numeric(12)]
    public string $originalTransactionNsu;
    #[Date(8, format: '!Ymd')]
    public DateTimeInterface $originalTransactionDate;

    #[Number(2)]
    public int $installmentNumber;

    #[Numeric(12)]
    public string $transactionNsu;
    #[Date(14, format: 'YmdHis')]
    public DateTimeInterface $transactionDate;

    #[Text(1)]
    public string $captureType;

    #[Numeric(18)]
    public string $envelopeId;
    #[Numeric(6)]
    public string $trace;

    #[Text(30)]
    public string $orderCode;

    #[Numeric(18)]
    public string $paywareFinantialId;
    #[Numeric(18)]
    public string $paywareAnticipationId;
    #[Numeric(18)]
    public string $paywarePaymentId;
    #[Numeric(18)]
    public string $paywareEstablishmentId;
    #[Numeric(18)]
    public string $paywareBranchId;

    #[Numeric(4)]
    public string $mcc;
    #[Numeric(9)]
    public string $paywareAcquirerSegregation;

    #[Numeric(12)]
    public string $cancellationAmountTotal;
    #[Numeric(12)]
    public string $installmentAmountTotal;
    #[Numeric(12)]
    public string $discountAmountTotal;
    #[Numeric(12)]
    public string $cancellationAmount;

    #[Numeric(12)]
    public string $originalTransactionAmountTotal;
    #[Numeric(12)]
    public string $originalTransactionAmount;
    #[Numeric(12)]
    public string $originalTransactionDiscountAmount;

    #[Number(4)]
    public int $originalTransactionInstallmentCount;
    #[Text(12)]
    public string $originalTransactionAuthorizationCode;

    #[Text(12)]
    public string $nsuTerminalHost;

    // Whitespace

    #[Number(6, index: 355)]
    public int $registryNseq;
}
