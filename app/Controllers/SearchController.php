<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\ContentRepository;
use App\Services\Language;

class SearchController
{
    private Environment $twig;
    private ContentRepository $content;
    private Language $langService;

    public function __construct(Language $langService)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
        $this->content = new ContentRepository();
        $this->langService = $langService;
    }

    public function search(string $lang): void
    {
        $query = trim($_GET['q'] ?? '');
        $results = [];
        if ($query !== '') {
            foreach (glob(__DIR__ . "/../../content/$lang/*.md") as $file) {
                $slug = basename($file, '.md');
                $page = $this->content->get($lang, $slug);
                if (stripos(strip_tags($page['body']), $query) !== false) {
                    $results[] = ['slug' => $slug, 'title' => $page['title']];
                }
            }
        }
        echo $this->twig->render('search.twig', [
            'lang' => $lang,
            'query' => $query,
            'results' => $results,
            'nav' => require __DIR__ . "/../../config/navigation.$lang.php",
            'switch' => $this->langService->switchUrl($lang, ''),
            'dir' => $this->langService->dir($lang),
            'title' => $lang === 'de' ? 'Suche' : 'بحث'
        ]);
    }
}
