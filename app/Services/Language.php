<?php
namespace App\Services;

class Language
{
    private array $supported;

    public function __construct(array $supported)
    {
        $this->supported = $supported;
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function switchUrl(string $current, string $slug): string
    {
        $other = $current === 'de' ? 'ar' : 'de';
        $slugPart = $slug ? '/' . $slug : '';
        return '/' . $other . $slugPart;
    }

    public function dir(string $lang): string
    {
        return $lang === 'ar' ? 'rtl' : 'ltr';
    }
}
