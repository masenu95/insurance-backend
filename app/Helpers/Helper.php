<?php

namespace App\Helpers;

use App\Services\EncryptionService;
use Carbon\Carbon;
use DOMException;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Spatie\ArrayToXml\ArrayToXml;

define('STATUS_CODE', 'TIRA001');
define('STATUS_DESCRIPTION', 'Success');

function parseArrayToXML($data, $root_tag, $pretty = false): string
{
    try {
        return $pretty ? (new ArrayToXml($data, $root_tag))->dropXmlDeclaration()->prettify()->toXml() : (new ArrayToXml($data, $root_tag))->dropXmlDeclaration()->toXml();
    } catch (DOMException $e) {
        report($e);
    }
}

function parseXMLtoArray($data): array
{
    $json = json_encode((array)simplexml_load_string($data));
    return json_decode($json, 1);
}

function logToCustom($data)
{
    Log::channel('tiramis')->info(json_encode($data, JSON_PRETTY_PRINT));
}

function formatDate($date): string
{
    return (new Carbon($date))->format('Y-m-d\\TH:i:s');
}

function wrapPayload($content): string
{
    return "<TiraMsg>{$content}<MsgSignature>" . EncryptionService::generateDigitalSignature($content) . "</MsgSignature></TiraMsg>";
}

function xmlResponseHandler($content)
{
    return response(wrapPayload($content), 200, ['Content-Type' => 'application/xml']);
}

function APIClient($content, $url): array
{
    $data = wrapPayload($content);

    logToCustom(parseXMLtoArray($data));

    $response = Http::withHeaders(['ClientCode' => env('CLIENT_CODE'), 'ClientKey' => env('CLIENT_KEY')])
        ->withBody($data, 'application/xml')
        ->post($url)
        ->body();

    $response = parseXMLtoArray($response);

    logToCustom($response);

    return $response;
}

function discordBot($content, $type = 'json'): Response
{
    switch ($type) {
        case 'json':
            $content = json_encode($content, JSON_PRETTY_PRINT);
            $content = "```json\n${content}```";
            break;
        case 'xml':
            $content = "```xml\n${content}```";
            break;
    }
    return Http::post(env('DISCORD_WEBHOOK_URL'), ['content' => $content]);
}

function generateRequestID(): string
{
    return 'TRM' . time();
}

function generateAcknowledgementID(): string
{
    return 'ACK' . time();
}


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


    static public function getImageMetadata($path)
{
    $metadata = exif_read_data(Storage::path($path));

    $location = 'undefined';
    $timeTaken = 'undefined';
    $latitude = 'undefined';
    $longitude = 'undefined';

    if (isset($metadata['GPSLatitude']) && isset($metadata['GPSLongitude'])) {
        $latitude = $metadata['GPSLatitude'];
        $longitude = $metadata['GPSLongitude'];

        // Convert the latitude and longitude to a location using any mapping service or library

       $location = strval($latitude) . ', ' . strval($longitude);
    }

    if (isset($metadata['DateTime'])) {
        $timeTaken = $metadata['DateTime'];
    }

    return [
        'location' => $location,
        'timeTaken' => $timeTaken,
        'lat'=>$latitude,
        'long'=>$longitude,
    ];
}

}
