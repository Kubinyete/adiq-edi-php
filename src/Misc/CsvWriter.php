<?php

namespace Kubinyete\Edi\Adiq\Misc;

use DateTimeInterface;
use Kubinyete\Edi\IO\StreamInterface;

class CsvWriter
{
    public function __construct(protected StreamInterface $stream, protected string $delimiter = ',', protected string $enclosure = '"', protected string $escape = '\\', protected string $eol = PHP_EOL)
    {
    }

    public function write(array $contents): void
    {
        $this->stream->write($this->pack($contents) . $this->eol, null);
        $this->stream->flush();
    }

    private function pack(array $contents): string
    {
        return implode($this->delimiter, array_map(fn ($x) => $this->enclose($x), array_values($contents)));
    }

    private function enclose($value): string
    {
        $value = $this->stringify($value);
        $value = str_replace($this->escape, $this->escape . $this->escape, $value);
        $value = str_replace($this->enclosure, $this->enclosure . $this->enclosure, $value);
        return $this->enclosure . $value . $this->enclosure;
    }

    private function stringify($value): string
    {
        if ($value instanceof DateTimeInterface) return $value->format(DateTimeInterface::RFC3339_EXTENDED);
        return strval($value);
    }
}
