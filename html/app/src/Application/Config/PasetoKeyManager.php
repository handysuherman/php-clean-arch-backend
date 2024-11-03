<?php

namespace app\src\Application\Config;

class PasetoKeyManager
{
    private string $paseto_private_key_path;
    const APP_PASETO_PRIVATE_KEY_PATH = "APP_PASETO_PRIVATE_KEY_PATH";

    private string $paseto_public_key_path;
    const APP_PASETO_PUBLIC_KEY_PATH = "APP_PASETO_PUBLIC_KEY_PATH";

    private string $private_key;

    private string $public_key;

    public function __construct()
    {
        if (!$_SERVER[self::APP_PASETO_PRIVATE_KEY_PATH]) {
            throw new \RuntimeException("paseto.private.key.path.undefined");
        }

        if (!$_SERVER[self::APP_PASETO_PUBLIC_KEY_PATH]) {
            throw new \RuntimeException("paseto.public.key.path.undefined");
        }

        $this->paseto_private_key_path = $_SERVER[self::APP_PASETO_PRIVATE_KEY_PATH];

        $this->paseto_public_key_path = $_SERVER[self::APP_PASETO_PUBLIC_KEY_PATH];

        $this->bootstrap();
    }

    public function getPrivateKey(): string
    {
        return $this->private_key;
    }

    public function getPublicKey(): string
    {
        return $this->public_key;
    }

    public function getPasetoPrivateKeyPath(): string
    {
        return $this->paseto_private_key_path;
    }

    public function getPasetoPublicKeyPath(): string
    {
        return $this->paseto_public_key_path;
    }

    private function bootstrap()
    {
        $private_key = file_get_contents($this->paseto_private_key_path);

        if ($private_key === false) {
            throw new \RuntimeException("Failed to load private key from $this->paseto_private_key_path");
        }

        $private_key = str_replace('-----BEGIN PRIVATE KEY-----', '', $private_key);
        $private_key = str_replace('-----END PRIVATE KEY-----', '', $private_key);
        $this->private_key = trim($private_key);

        $public_key = file_get_contents($this->paseto_public_key_path);
        if ($public_key === false) {
            throw new \RuntimeException("Failed to load public key from  $this->paseto_public_key_path");
        }

        $public_key = str_replace('-----BEGIN PUBLIC KEY-----', '', $public_key);
        $public_key = str_replace('-----END PUBLIC KEY-----', '', $public_key);
        $this->public_key = trim($public_key);
    }
}
