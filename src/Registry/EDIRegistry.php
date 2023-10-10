<?php

namespace Kubinyete\Edi\Adiq\Registry;

use Kubinyete\Edi\Registry\Registry;

abstract class EDIRegistry extends Registry
{
    public const TYPE_HEADER_START = 'A0';
    public const TYPE_HEADER_END = 'A9';
    public const TYPE_TRANSACTION_BATCH_START = 'L0';
    public const TYPE_TRANSACTION_BATCH_END = 'L9';
    public const TYPE_SALE_RECEIPT = 'CV';
    public const TYPE_CANCEL_RECEIPT = 'CC';
    public const TYPE_ADJUSTMENT = 'AJ';
}
