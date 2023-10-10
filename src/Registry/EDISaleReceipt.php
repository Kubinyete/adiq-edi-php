<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Registry\Field\Numeric;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDISaleReceipt extends EDIRegistry
{
    public const ENTRY_TYPE_PROMISE = '0';
    public const ENTRY_TYPE_LIQUIDATION = '1';
    public const ENTRY_TYPE_ANTECIPATED_LIQUIDATION = '2';

    public const PRODUCT_TYPE_CREDIT = 'C';
    public const PRODUCT_TYPE_DEBIT = 'D';
    public const PRODUCT_TYPE_VOUCHER = 'V';

    public const CAPTURE_TYPE_MANUAL = '1';
    public const CAPTURE_TYPE_POS = '2';
    public const CAPTURE_TYPE_PDV = '3';
    public const CAPTURE_TYPE_TRANSACTION_OFF = '4';
    public const CAPTURE_TYPE_INTERNET = '5';
    public const CAPTURE_TYPE_URA = '6';
    public const CAPTURE_TYPE_UNDEFINED = '8';
    public const CAPTURE_TYPE_OTHERS = '9';

    public const TRANSACTION_TYPE_NORMAL = '00';
    public const TRANSACTION_TYPE_DCC = '01';
    public const TRANSACTION_TYPE_PLUS_PRICE = '02';
    public const TRANSACTION_TYPE_TAX_PLUS_TARIFF = '03';
    public const TRANSACTION_TYPE_ECOMM = '04';

    #[Text(2)]
    public string $registryCode;
    #[Numeric(15)]
    public string $establishmentCode;

    #[Numeric(12)]
    public string $transactionNsu;
    #[Date(14, format: 'YmdHis')]
    public DateTimeInterface $transactionDate;

    #[Text(1)]
    public string $entryType;
    #[Date(8, format: '!Ymd')]
    public DateTimeInterface $entryDate;
    #[Text(1)]
    public string $productType;

    #[Text(1)]
    public string $captureType;

    #[Numeric(11)]
    public string $saleAmountTotal;
    #[Numeric(11)]
    public string $saleAmountTax;
    #[Numeric(11)]
    public string $saleAmount;

    #[Text(19)]
    public string $cardNumber;

    #[Number(2)]
    public int $installmentNumber;
    #[Number(2)]
    public int $installmentCount;
    #[Numeric(12)]
    public string $installmentNsu;

    #[Numeric(11)]
    public string $installmentAmountTotal;
    #[Numeric(11)]
    public string $installmentAmountTax;
    #[Numeric(11)]
    public string $installmentAmount;

    #[Text(3)]
    public string $bankCode;
    #[Text(6)]
    public string $agencyNumber;
    #[Text(11)]
    public string $accountNumber;

    #[Text(12)]
    public string $authorizationCode;

    #[Number(3)]
    public int $brandCode;
    #[Number(3)]
    public int $productCode;

    #[Numeric(11)]
    public string $taxInterchangeAmount;
    #[Numeric(11)]
    public string $taxAmount;
    #[Numeric(11)]
    public string $taxInstallmentInterchangeAmount;
    #[Numeric(11)]
    public string $taxInstallmentAmount;
    #[Numeric(11)]
    public string $multiborderReducingAmount;

    #[Numeric(11)]
    public string $anticipationTaxAmount;
    #[Numeric(11)]
    public string $anticipationAmount;

    #[Numeric(2)]
    public string $transactionType;
    #[Text(30)]
    public string $orderCode;

    #[Text(3)]
    public string $countryCode;
    #[Text(8)]
    public string $terminalId;
    #[Numeric(18)]
    public string $envelopeId;
    #[Numeric(6)]
    public string $trace;

    #[Numeric(20)]
    public string $paymentAccountNumber;
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

    #[Number(6, index: 500)]
    public int $registryNseq;
}
