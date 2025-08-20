<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\ContentRepository;
use App\Services\Language;
use App\Services\BlogRepository;

class PageController
{
    private Environment $twig;
    private ContentRepository $content;
    private Language $langService;
    private BlogRepository $blog;

    public function __construct(Language $langService)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
        $this->content = new ContentRepository();
        $this->langService = $langService;
        $this->blog = new BlogRepository();
    }

    public function show(string $lang, string $slug = 'index'): void
    {
        $data = $this->content->get($lang, $slug);
        if (!$data) {
            http_response_code(404);
            echo $this->twig->render('errors/404.twig', [
                'lang' => $lang,
                'dir' => $this->langService->dir($lang),
                'nav' => require __DIR__ . "/../../config/navigation.$lang.php",
                'switch' => $this->langService->switchUrl($lang, ''),
                'title' => '404'
            ]);
            return;
        }
        $posts = [];
        if ($slug === 'index') {
            $posts = $this->blog->visible($lang, new \DateTime());
        }
        echo $this->twig->render('page.twig', [
            'lang' => $lang,
            'title' => $data['title'],
            'content' => $data['body'],
            'meta' => $data['meta'],
            'nav' => require __DIR__ . "/../../config/navigation.$lang.php",
            'switch' => $this->langService->switchUrl($lang, $slug),
            'dir' => $this->langService->dir($lang),
            'slug' => $slug,
            'posts' => $posts
        ]);
    }
}
