<?php
namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class QrCodeService
{
    private $httpClient;
    private $storagePath;

    public function __construct(HttpClientInterface $httpClient, string $storagePath)
    {
        $this->httpClient = $httpClient;
        $this->storagePath = $storagePath;
    }

    public function generateQrCode(array $data, string $filename): string
    {
        $encodedData = urlencode(json_encode($data));
        $url = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=$encodedData";

        $response = $this->httpClient->request('GET', $url);
        $imageContent = $response->getContent();

        $filePath = $this->storagePath . '/' . $filename;
        file_put_contents($filePath, $imageContent);

        return '/qr_codes/' . $filename;
    }
}