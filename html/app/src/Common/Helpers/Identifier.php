<?php

namespace app\src\Common\Helpers;

use Exception;
use Ramsey\Uuid\Uuid;
use Ulid\Ulid;

class Identifier
{
    const KEY_LENGTH = 12;

    public static function newUUIDV4(): string
    {
        return Uuid::uuid4()->toString();
    }

    public static function newULID(): string
    {
        return Ulid::generate()->__toString();
    }

    public static function encrypt(string $text): string
    {
        $nonce = self::chachae20poly103_nonce();
        $key = self::chacha20poly1305_keygen();
        $chiper_text = sodium_crypto_aead_chacha20poly1305_encrypt($text, '', $nonce, $key);

        return URL::safe_base64_encode(bin2hex($nonce . $chiper_text));
    }

    public static function decrypt(string $text): string
    {
        $decoded_text = URL::safe_base64_decode($text);

        // metadata
        $nonce_length = SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES;
        $nonce = substr($decoded_text, 0, $nonce_length);
        $key = substr($decoded_text, -SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES);
        $chiper_text = substr($decoded_text, $nonce_length, -SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_KEYBYTES);

        $plain_text = sodium_crypto_aead_chacha20poly1305_decrypt($chiper_text, '', $nonce, $key);

        if ($plain_text === false) {
            throw new Exception("decoding failed");
        }

        return $plain_text;
    }
    
    private static function chacha20poly1305_keygen(): string
    {
        return sodium_crypto_aead_chacha20poly1305_keygen();
    }

    private static function chachae20poly103_nonce(): string
    {
        return random_bytes(SODIUM_CRYPTO_AEAD_CHACHA20POLY1305_NPUBBYTES);
    }
}
