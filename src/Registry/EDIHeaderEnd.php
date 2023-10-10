<?php

namespace Kubinyete\Edi\Adiq\Registry;

use DateTimeInterface;
use Kubinyete\Edi\Registry\Field\Date;
use Kubinyete\Edi\Registry\Field\Text;
use Kubinyete\Edi\Registry\Field\Number;
use Kubinyete\Edi\Registry\Field\Numeric;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;

class EDIHeaderEnd extends EDIRegistry
{
    #[Text(2)]
    public string $registryCode;

    #[Number(6)]
    public int $registryTotalCount;

    #[Number(6)]
    public int $registryNseq;
}
