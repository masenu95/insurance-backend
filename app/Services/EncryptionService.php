<?php

namespace App\Services;

use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Support\Facades\Storage;

define('ALGORITHM', 'sha1WithRSAEncryption');
define('KEY_PASS_PRIVATE', 'ottimale@2021');
define('KEY_PASS_CLIENT', ' ottimaleclients2021');

class EncryptionService
{
    public static function generateDigitalSignature($content)
    {
        try {
            $cert_store = Storage::disk('local')->get('keys/ottimale.pfx');
            openssl_pkcs12_read($cert_store, $cert_info, KEY_PASS_PRIVATE);
            openssl_sign($content, $signature, $cert_info['pkey'], ALGORITHM);
            return base64_encode($signature);
        } catch (FileNotFoundException $e) {
            report($e);
            return false;
        }
    }

    public static function verifyDigitalSignature($content, $signature)
    {
        try {
            $pcert_store = Storage::disk('local')->get('keys/ottimalepublic.pfx');
            openssl_pkcs12_read($pcert_store, $pcert_info, 'KEY_PASS_CLIENT');
            $raw_sig = base64_decode($signature);
            return openssl_verify($content, $raw_sig, $pcert_info['extracerts']['0']);
        } catch (FileNotFoundException $e) {
            report($e);
            return false;
        }
    }
}
