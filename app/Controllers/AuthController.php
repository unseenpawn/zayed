<?php
namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;
use App\Services\Language;

class AuthController
{
    private Environment $twig;
    private Language $langService;

    public function __construct(Language $langService)
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Views');
        $this->twig = new Environment($loader);
        $this->langService = $langService;
    }

    public function login(): void
    {
        session_start();
        $error = '';
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $user = $_POST['username'] ?? '';
            $pass = $_POST['password'] ?? '';
            if ($user === 'admin' && $pass === 'secret') {
                $_SESSION['user'] = 'admin';
                header('Location: /admin/blog');
                exit;
            }
            $error = 'Invalid credentials';
        }
        echo $this->twig->render('login.twig', [
            'title' => 'Login',
            'error' => $error,
        ]);
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header('Location: /');
    }
}
