<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Registry\Field\Numeric;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDIHeader extends EDIRegistry
{
    public const PROCESSING_TYPE_NORMAL = 'N';
    public const PROCESSING_TYPE_REPROCESSED = 'R';

    #[Text(2)]
    public string $registryCode;

    #[Text(6, padDirection: STR_PAD_LEFT, padChar: '0')]
    public string $layoutVersion;
    #[Date(14, format: 'YmdHis')]
    public DateTimeInterface $fileDate;

    #[Number(6)]
    public int $movementId;
    #[Text(30)]
    public string $acquirerName;
    #[Numeric(4)]
    public string $serviceProviderId;
    #[Numeric(9)]
    public string $establishmentCode;
    #[Text(1)]
    public string $processingType;

    #[Number(6)]
    public int $registryNseq;
}
