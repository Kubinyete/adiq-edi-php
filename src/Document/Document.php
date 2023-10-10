<?php

namespace Kubinyete\Edi\Adiq\Document;

use RuntimeException;
use Kubinyete\Edi\IO\Stream;
use Kubinyete\Edi\Parser\LineParser;
use Kubinyete\Edi\IO\StreamInterface;
use Kubinyete\Edi\Adiq\Parser\EDIParser;
use Kubinyete\Edi\Adiq\Registry\EDIHeader;

class Document
{
    private EDIParser $parser;
    private ?EDIHeader $header;

    public function __construct(private StreamInterface $stream)
    {
        $this->parser = new EDIParser($stream);
        $this->load();
    }

    public function load(): void
    {
        $this->parser->rollback();
        $this->header = $this->parser->next();

        $this->changeParserVersion($this->header);
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
