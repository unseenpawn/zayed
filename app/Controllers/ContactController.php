<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\ContentRepository;
use App\Services\Language;

class ContactController
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
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function form(string $lang): void
    {
        $slug = 'kontakt';
        $errors = [];
        $success = false;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            if ($name === '') { $errors[] = 'name'; }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) { $errors[] = 'email'; }
            if ($message === '') { $errors[] = 'message'; }
            if (!$errors) {
                $success = true; // here mail() would be called
            }
        }
        $data = $this->content->get($lang, $slug);
        echo $this->twig->render('contact.twig', [
            'lang' => $lang,
            'title' => $data['title'],
            'content' => $data['body'],
            'nav' => require __DIR__ . "/../../config/navigation.$lang.php",
            'switch' => $this->langService->switchUrl($lang, $slug),
            'dir' => $this->langService->dir($lang),
            'errors' => $errors,
            'success' => $success,
            'meta' => $data['meta']
        ]);
    }
}
