<?php

namespace Sfx\UnifiPortal;

if (!defined('UNI_PLUGIN_PATH')) {
    exit;
}
/**
 * Class Utils
 *
 * Provides utility methods for common operations.
 */
class Utils
{

    /**
     * XOR encrypt a string.
     *
     * @param string $str The string to encrypt.
     * @param string $password The password used for encryption.
     * @return string The encrypted string.
     */
    protected static function _xor($str, $password = '')
    {
        $len   = strlen($str);
        $gamma = '';
        $n     = $len > 100 ? 8 : 2;
        while (strlen($gamma) < $len) {
            $gamma .= substr(pack('H*', sha1($password . $gamma)), 0, $n);
        }

        return $str ^ $gamma;
    }

    /**
     * URL-safe Base64 encode a string after XOR encryption.
     *
     * @param string $str The string to encrypt.
     * @param string $password The password used for encryption.
     * @return string The URL-safe Base64-encoded encrypted string.
     */
    public static function xorEncrypt($str, $password = '')
    {
        return self::base64UrlEncode(self::_xor($str, $password));
    }

    /**
     * XOR decrypt a URL-safe Base64-encoded string.
     *
     * @param string $str The URL-safe Base64-encoded string to decrypt.
     * @param string $password The password used for decryption.
     * @return string The decrypted string.
     */
    public static function xorDecrypt($str, $password = '')
    {
        return self::_xor(self::base64UrlDecode($str), $password);
    }

    /**
     * URL-safe Base64 encode a string.
     *
     * @param string $data The data to encode.
     * @return string The URL-safe Base64 encoded string.
     */
    public static function base64UrlEncode($data)
    {
        return strtr(self::base64Encode($data), '+/', '-_');
    }

    /**
     * URL-safe Base64 decode a string.
     *
     * @param string $data The URL-safe Base64 encoded string to decode.
     * @return string The decoded string.
     */
    public static function base64UrlDecode($data)
    {
        $data = strtr($data, '-_', '+/');
        return self::base64Decode($data);
    }

    /**
     * Encodes data to Base64 format.
     *
     * @param string $data The data to encode.
     * @return string The Base64 encoded string.
     */
    public static function base64Encode(string $data): string
    {
        return base64_encode($data);
    }

    /**
     * Decodes a Base64 encoded string.
     *
     * @param string $data The Base64 encoded string to decode.
     * @return string The decoded string.
     * @throws \InvalidArgumentException If the provided string is not valid Base64.
     */
    public static function base64Decode(string $data): string
    {
        $decoded = base64_decode($data, true);
        if ($decoded === false) {
            throw new \InvalidArgumentException('Invalid Base64 string provided.');
        }
        return $decoded;
    }

    /**
     * Generate a random SHA1 hash.
     *
     * @param string $salt The salt value to use in generating the hash. Default is SBP_PLUGIN_SLUG.
     * @param int $length The length of the hash. Default is 40.
     * @return string The generated SHA1 hash.
     */
    public static function sha1($salt = UNI_PLUGIN_SLUG, $length = 40)
    {
        $max    = ceil($length / 40);
        $random = '';
        for ($i = 0; $i < $max; $i++) {
            $random .= sha1(microtime(true) . mt_rand(10000, 90000) . $salt);
        }
        return substr($random, 0, $length);
    }
}
