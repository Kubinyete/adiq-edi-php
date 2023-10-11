# Adiq EDI PHP
A standard library for loading EDI files from Adiq.

### Usage
You can just instantiate a new document object from a data stream, after that, you should be able to directly
check each envelope that is present on file, and iterate over each entry accordingly.

```php
$path = __DIR__ . DIRECTORY_SEPARATOR . 'EDI_020_20231001_11111_0011_001111111_000111.txt';

$document = Document::open($path);

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