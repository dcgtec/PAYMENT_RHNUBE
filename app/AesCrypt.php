<?php

namespace App;

class AesCrypt
{

    private const ALGORITHM = 'aes-256-cbc';
    private const KEY = 'gHu@GbWv:Kxy<n^d';
    private const INITVECTOR = 'cV2TAwm3f5IOTGCV';

    public static function encrypt($wValor)
    {
        $encrypted = openssl_encrypt($wValor, self::ALGORITHM, self::KEY, OPENSSL_RAW_DATA, self::INITVECTOR);
        return base64_encode($encrypted);
    }

    public static function decrypt($wValue)
    {
        $decrypted = openssl_decrypt(base64_decode($wValue), self::ALGORITHM, self::KEY, OPENSSL_RAW_DATA, self::INITVECTOR);
        return $decrypted;
    }
}
