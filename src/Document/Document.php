<?php

namespace Kubinyete\Edi\Adiq\Document;

use Closure;
use RuntimeException;
use Kubinyete\Edi\IO\Stream;
use Kubinyete\Edi\Parser\LineParser;
use Kubinyete\Edi\IO\StreamInterface;
use Kubinyete\Edi\Adiq\Parser\EDIParser;
use Kubinyete\Edi\Adiq\Registry\EDIHeader;
use Kubinyete\Edi\Adiq\Registry\EDIRegistry;
use Kubinyete\Edi\Adiq\Registry\EDIHeaderEnd;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatch;
use Kubinyete\Edi\Adiq\Registry\EDITransactionBatchEnd;

class Document
{
    private EDIParser $parser;
    private ?DocumentMetadata $metadata;
    private ?iterable $envelopes;

    public function __construct(private StreamInterface $stream, bool $autoload = true)
    {
        $this->parser = EDIParser::loadFromStream($stream);
        $this->metadata = null;

        if ($autoload) {
            $this->load();
        }
    }

    public function load(): void
    {
        $header = $this->eatUntilRegistry(EDIHeader::class);
        $this->changeParserVersion($header);
        $this->loadMetadata($header);
    }

    public function getMetadata(): DocumentMetadata
    {
        return $this->metadata;
    }

    public function getEnvelopes(): iterable
    {
        return $this->envelopes;
    }

    public function eachItemFromLine(int $line, int $to = 0): iterable
    {
        $this->parser->goto($line);
        yield from $this->eatRejectedUntilLine($to);
    }

    //

    private function loadMetadata(EDIHeader $header): void
    {
        while ($head = $this->parser->next()) {
            if ($head instanceof EDITransactionBatch) {
                $this->loadEnvelopes($head);
            } else {
                break;
            }
        }

        if (!$head instanceof EDIHeaderEnd) {
            throw new RuntimeException("Expected ending registry, found EOF or unknown registry type");
        }

        $this->metadata = new DocumentMetadata($header, $head);
    }

    private function loadEnvelopes(EDITransactionBatch $head): void
    {
        $lineStart = $this->parser->getLineNumber();
        $tail = $this->eatUntilRegistry(EDITransactionBatchEnd::class);
        $lineEnd = $this->parser->getLineNumber();
        $this->envelopes[] = new Envelope($head, $tail, $lineStart, $lineEnd, $this);
    }

    private function eatUntilRegistry(string $registryClass, ?Closure $reject = null): ?EDIRegistry
    {
        $registry = null;

        do {
            if ($registry && $reject) $reject($registry);
            $registry = $this->parser->next();
        } while ($registry && !$registry instanceof $registryClass);

        return $registry;
    }

    private function eatUntilLine(int $lineNo, ?Closure $reject = null): ?EDIRegistry
    {
        $registry = null;

        do {
            if ($registry && $reject) $reject($registry);
            $registry = $this->parser->next();
        } while ($registry && ($lineNo == 0 || $this->parser->getLineNumber() < $lineNo));

        return $registry;
    }

    private function eatRejectedUntilLine(int $lineNo): iterable
    {
        $registry = null;

        do {
            if ($registry) yield $registry;
            $registry = $this->parser->next();
        } while ($registry && ($lineNo == 0 || $this->parser->getLineNumber() < $lineNo));
    }

    private function changeParserVersion(EDIHeader $header): void
    {
        if (!$this->parser->isVersionSupported($header->layoutVersion)) {
            $this->parser = $this->loadParserVersion($header->layoutVersion);
        }
    }

    private function loadParserVersion(string $version): EDIParser
    {
        // @TODO: Dynamically change parsers on the fly
        $versionString = implode(', ', $this->parser->getVersionSupport());
        throw new RuntimeException("Only version {$versionString} is supported for this document");
    }

    //

    public static function open(string $path): self
    {
        return new self(Stream::file($path, 'rb'));
    }

    public static function stream(StreamInterface $stream): self
    {
        return new self($stream);
    }
}
