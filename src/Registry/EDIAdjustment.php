<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Registry\Field\Numeric;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDIAdjustment extends EDIRegistry
{
    public const ENTRY_TYPE_PROMISE = '0';
    public const ENTRY_TYPE_LIQUIDATION = '1';
    public const ENTRY_TYPE_ANTECIPATED_LIQUIDATION = '2';

    public const CAPTURE_TYPE_MANUAL = '1';
    public const CAPTURE_TYPE_POS = '2';
    public const CAPTURE_TYPE_PDV = '3';
    public const CAPTURE_TYPE_TRANSACTION_OFF = '4';
    public const CAPTURE_TYPE_INTERNET = '5';
    public const CAPTURE_TYPE_URA = '6';
    public const CAPTURE_TYPE_UNDEFINED = '8';
    public const CAPTURE_TYPE_OTHERS = '9';

    public const ADJUSTMENT_TYPE_CREDIT = '1';
    public const ADJUSTMENT_TYPE_DEBIT = '2';

    #[Text(2)]
    public string $registryCode;
    #[Numeric(15)]
    public string $establishmentCode;

    #[Numeric(12)]
    public string $originalTransactionNsu;
    #[Date(8, format: '!Ymd')]
    public DateTimeInterface $originalTransactionDate;

    #[Number(2)]
    public int $installment;

    #[Numeric(12)]
    public string $transactionNsu;
    #[Date(14, format: 'YmdHis')]
    public DateTimeInterface $transactionDate;

    #[Text(1)]
    public string $entryType;
    #[Date(8, format: '!Ymd')]
    public DateTimeInterface $entryDate;

    #[Text(1)]
    public string $captureType;

    #[Text(1)]
    public string $adjustmentType;
    #[Text(3)]
    public string $adjustmentCode;
    #[Text(30)]
    public string $adjustmentDescription;

    #[Numeric(11)]
    public string $adjusmentAmountTotal;
    #[Numeric(11)]
    public string $adjustmentDiscountAmount;
    #[Numeric(11)]
    public string $adjustmentAmount;

    #[Text(3)]
    public string $bankCode;
    #[Text(6)]
    public string $agencyNumber;
    #[Text(11)]
    public string $accountNumber;

    #[Text(19)]
    public string $originalTransactionCardNumber;

    #[Number(3)]
    public int $brandCode;
    #[Number(3)]
    public int $productCode;

    #[Numeric(11)]
    public string $anticipationTaxAmount;
    #[Numeric(11)]
    public string $anticipationAmount;

    #[Numeric(18)]
    public string $envelopeId;
    #[Numeric(6)]
    public string $trace;

    #[Numeric(20)]
    public string $paymentAccountNumber;
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
    #[Text(12)]
    public string $nsuTerminalHost;

    // Whitespace

    #[Number(6, index: 418)]
    public int $registryNseq;
}
