<?php

namespace App\Helpers;

use App\Services\EncryptionService;
use Carbon\Carbon;
use DOMException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Spatie\ArrayToXml\ArrayToXml;

class Helper
{
    public static function parseArrayToXML($data, $root_tag): string
    {
        try {
            return (new ArrayToXml($data, $root_tag))->dropXmlDeclaration()->/*prettify()->*/ toXml();
        } catch (DOMException $e) {
            report($e);
            return false;
        }
    }

    public static function parseXMLtoArray($data): array
    {
        $json = json_encode((array)simplexml_load_string($data));
        return json_decode($json, 1);
    }

    public static function logger($data)
    {
        Log::channel('tiramis')->info(json_encode($data, JSON_PRETTY_PRINT));
    }

    public static function dateFormatter($date): string
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d\\TH:i:s');
    }

    public static function payloadWrapper($content): string
    {
        return "<TiraMsg>{$content}<MsgSignature>" . EncryptionService::generateDigitalSignature($content) . "</MsgSignature></TiraMsg>";
    }

    public static function responseWrapper($content)
    {
        $data = Helper::payloadWrapper($content);

        return response($data, 200, ['Content-Type' => 'application/xml']);
    }

    public function APIClient($content, $url): array
    {
        $data = $this->payloadWrapper($content);

        $this->logger($this->parseXMLtoArray($data));

        $response = Http::withHeaders([
            'ClientCode' => env('CLIENT_CODE'),
            'ClientKey' => env('CLIENT_KEY')
        ])->withBody($data, 'application/xml')->post($url);

        $response = $this->parseXMLtoArray($response->body());

        $this->logger($response);

        return $response;
    }
}
