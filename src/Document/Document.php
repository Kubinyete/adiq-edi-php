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

        if ($autoload) {
            $this->load();
        }
    }

    public function load(): void
    {
        $header = $this->eat(EDIHeader::class);
        $this->changeParserVersion($header);
        $this->loadBody($header);
    }

    public function envelopes(): iterable
    {
        return $this->envelopes;
    }

    public function eachEnvelopeItem(Envelope $envelope): iterable
    {
        $this->parser->rollback();
        $registry = null;

        do {
            $registry = $this->parser->next();
        } while ($registry && $this->parser->getLineNumber() < $envelope->lineStart);

        if ($this->parser->getLineNumber() >= $envelope->lineStart) {
            while ($registry && $this->parser->getLineNumber() < $envelope->lineEnd - 1) {
                $registry = $this->parser->next();
                yield $registry;
            }
        }
    }

    //

    private function loadBody(EDIHeader $header): void
    {
        while ($head = $this->parser->next()) {
            if ($head instanceof EDITransactionBatch) {
                $lineStart = $this->parser->getLineNumber();
                $tail = $this->eat(EDITransactionBatchEnd::class);
                $lineEnd = $this->parser->getLineNumber();

                $this->envelopes[] = new Envelope($head, $tail, $lineStart, $lineEnd, $this);
            } else if ($head instanceof EDIHeaderEnd) {
                $this->metadata = new DocumentMetadata($header, $head);
            }
        }

        if (!$this->metadata) {
            throw new RuntimeException("Expected ending registry, found EOF");
        }
    }

    private function eat(string $registryClass, ?Closure $reject = null): ?EDIRegistry
    {
        $registry = null;

        do {
            if ($registry && $reject) $reject($registry);
            $registry = $this->parser->next();
        } while ($registry && !$registry instanceof $registryClass);

        return $registry;
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
}
