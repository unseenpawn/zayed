<?php
namespace App\Services;

class BlogRepository
{
    private string $file;

    public function __construct(string $file = __DIR__ . '/../../content/blog.json')
    {
        $this->file = $file;
        if (!file_exists($this->file)) {
            file_put_contents($this->file, json_encode([]));
        }
    }

    /**
     * Return all posts visible for given language and date.
     * @param string $lang
     * @param \DateTimeInterface $now
     * @return array
     */
    public function visible(string $lang, \DateTimeInterface $now): array
    {
        $posts = json_decode(file_get_contents($this->file), true);
        if (!is_array($posts)) {
            return [];
        }
        return array_values(array_filter($posts, function ($post) use ($lang, $now) {
            return ($post['lang'] ?? 'de') === $lang
                && $now >= new \DateTime($post['start'])
                && $now <= new \DateTime($post['end']);
        }));
    }

    /**
     * Append a new post.
     * @param array $data
     */
    public function add(array $data): void
    {
        $posts = json_decode(file_get_contents($this->file), true);
        if (!is_array($posts)) {
            $posts = [];
        }
        $posts[] = $data;
        file_put_contents($this->file, json_encode($posts, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }
}
