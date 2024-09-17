
# <img align="middle" src="https://www.adiq.com.br/images/logo.png" width="128"> **EDI** for PHP

**pt-BR**: Uma biblioteca simples e direta para carregar arquivos EDI da adquirente [Adiq Pagamentos](https://www.adiq.com.br/)

**en-US**: A straightfoward library for loading EDI files from [Adiq Pagamentos](https://www.adiq.com.br/)

***NOTA**: Este guia está primariamente em inglês, caso haja necessidade, será adicionado uma versão em pt-BR no futuro.*

---

### Installation

Let's start by requiring the package by running the following command
```sh
composer require kubinyete/adiq-edi-php
```

### Usage
You can just instantiate a new document object from a data stream, after that, you should be able to directly
check each envelope that is present on file, and iterate over each entry accordingly.

```php
// Opening the document by providing a file path
$document = Document::open(__DIR__ . DIRECTORY_SEPARATOR . 'EDI_020_20231001_11111_0011_001111111_000111.txt');
// Metadata information can be found via
$metadata = $document->getMetadata();

dump([
    'fileVersion' => $metadata->version,
    'fileDate' => $metadata->date,
    'movement' => $metadata->movement,
    'acquirerName' => $metadata->acquirer,
    'establishmentCode' => $metadata->establishmentCode,
]);

// For each envelope available
foreach ($document->getEnvelopes() as $envelope) {
    /** @var Envelope $envelope */
    dump([
        'envelopeDate' => $envelope->date,
        'envelopeCurrencyCode' => $envelope->currencyCode,
        'entriesCount' => $envelope->registryTotalCount,
        'entriesCreditSum' => $envelope->registryTotalCreditAmount,
    ]);

    // For each entry (CV, AJ, CC) inside our envelope.
    foreach ($envelope->getEntries() as $entry) {
        /** @var EDIRegistry $entry */
        dump($registry);
    }
}
```