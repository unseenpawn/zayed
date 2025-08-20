<?php
namespace App\Services;

use Symfony\Component\Yaml\Yaml;
use League\CommonMark\CommonMarkConverter;

class ContentRepository
{
    private string $basePath;
    private CommonMarkConverter $markdown;

    public function __construct(string $basePath = __DIR__ . '/../../content')
    {
        $this->basePath = $basePath;
        $this->markdown = new CommonMarkConverter();
    }

    public function get(string $lang, string $slug): ?array
    {
        $file = sprintf('%s/%s/%s.md', $this->basePath, $lang, $slug);
        if (!is_file($file)) {
            return null;
        }
        $raw = file_get_contents($file);
        $meta = [];
        $body = $raw;
        if (preg_match('/^---\s*\n(.*)\n---\s*\n(.*)$/s', $raw, $m)) {
            $meta = Yaml::parse($m[1]);
            $body = $m[2];
        }
        $html = $this->markdown->convert($body);
        return ['meta' => $meta, 'title' => $meta['title'] ?? '', 'body' => $html];
    }
}
