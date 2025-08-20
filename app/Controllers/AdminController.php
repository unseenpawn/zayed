<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\Language;
use App\Services\BlogRepository;

class AdminController
{
    private Environment $twig;
    private BlogRepository $blog;
    private Language $langService;

    public function __construct(Language $langService)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
        $this->blog = new BlogRepository();
        $this->langService = $langService;
    }

    public function blog(): void
    {
        session_start();
        if (($_SESSION['user'] ?? '') !== 'admin') {
            header('Location: /login');
            return;
        }
        $message = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'title' => $_POST['title'] ?? '',
                'content' => $_POST['content'] ?? '',
                'start' => $_POST['start'] ?? '',
                'end' => $_POST['end'] ?? '',
                'lang' => 'de'
            ];
            $this->blog->add($data);
            $message = 'Entry saved';
        }
        echo $this->twig->render('admin_blog.twig', [
            'title' => 'Blog Admin',
            'message' => $message,
        ]);
    }
}
