<?php

namespace Kubinyete\Edi\Adiq\Document;

use DateTimeInterface;
use Kubinyete\Edi\Adiq\Registry\EDIHeader;
use Kubinyete\Edi\Adiq\Registry\EDIHeaderEnd;

class DocumentMetadata
{
    public string $version;
    public DateTimeInterface $date;
    public int $movement;
    public string $acquirer;
    public string $establishmentCode;
    public string $processingType;

    public int $registryTotalCount;

    public function __construct(EDIHeader $header, EDIHeaderEnd $tail)
    {
        $this->version = $header->layoutVersion;
        $this->date = $header->fileDate;
        $this->movement = $header->movementId;
        $this->acquirer = $header->acquirerName;
        $this->establishmentCode = $header->establishmentCode;

        $this->processingType = $header->processingType;
        $this->registryTotalCount = $tail->registryTotalCount;
    }

    public function isReprocessed(): bool
    {
        return $this->processingType === EDIHeader::PROCESSING_TYPE_REPROCESSED;
    }
}
