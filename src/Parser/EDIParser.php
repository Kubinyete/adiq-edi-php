<?php

namespace Kubinyete\Edi\Adiq\Parser;

use Kubinyete\Edi\IO\Stream;
use UnexpectedValueException;
use Kubinyete\Edi\Parser\LineParser;
use Kubinyete\Edi\Registry\Registry;
use Kubinyete\Edi\IO\StreamInterface;
use Kubinyete\Edi\Parser\LineContext;
use Kubinyete\Edi\Adiq\Registry\EDIHeader;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;
use Kubinyete\Edi\Adiq\Registry\EDIHeaderEnd;
use Kubinyete\Edi\Adiq\Registry\EDIAdjustment;
use Kubinyete\Edi\Adiq\Registry\EDISaleReceipt;
use Kubinyete\Edi\Adiq\Registry\EDICancelReceipt;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatch;
use Kubinyete\Edi\Registry\Exception\FieldException;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatchEnd;

class EDIParser extends LineParser
{
    private const VERSION_SUPPORT = ['3.1'];

    public function getVersionSupport(): array
    {
        return self::VERSION_SUPPORT;
    }

    public function isVersionSupported(string $version): bool
    {
        return in_array($version, $this->getVersionSupport(), true);
    }

    protected function __construct(StreamInterface $stream)
    {
        parent::__construct($stream);
    }

    protected function parse(LineContext $ctx): ?Registry
    {
        if ($ctx->empty()) return null;

        [$contents, $number] = $ctx->unwrap();
        $code = substr($contents, 0, 2);

        try {
            return match ($code) {
                EDIRegistry::TYPE_HEADER_START => EDIHeader::from($contents),
                EDIRegistry::TYPE_HEADER_END => EDIHeaderEnd::from($contents),
                EDIRegistry::TYPE_TRANSACTION_BATCH_START => EDITransactionBatch::from($contents),
                EDIRegistry::TYPE_TRANSACTION_BATCH_END => EDITransactionBatchEnd::from($contents),
                EDIRegistry::TYPE_SALE_RECEIPT => EDISaleReceipt::from($contents),
                EDIRegistry::TYPE_ADJUSTMENT => EDIAdjustment::from($contents),
                EDIRegistry::TYPE_CANCEL_RECEIPT => EDICancelReceipt::from($contents),
                default => $ctx->raise("Cannot parse EDI of type '$code'", 0),
            };
        } catch (FieldException $e) {
            $ctx->raise("[{$e->getName()}] {$e->getMessage()}", $e->getCursor());
        }
    }

    //

    public static function loadFromFile(string $path): static
    {
        return new static(Stream::file($path, 'rb'));
    }

    public static function loadFromStream(StreamInterface $stream): static
    {
        return new static($stream);
    }
}
