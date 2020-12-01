<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Model\HomeManager;
use App\Model\ArticleManager;
use App\Model\ModelManager;
use App\Model\BrandManager;
use App\Model\ColorManager;
use App\Model\CarousselManager;
use App\Service\CartService;

class HomeController extends AbstractController
{

    /**
     * Display home page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $homeManager = new HomeManager();
        $articles = $homeManager->selectAll();
        $carousselManager = new CarousselManager();
        $caroussels = $carousselManager->selectAll();
        return $this->twig->render('Home/index.html.twig', [
            'articles' => $articles,
            'caroussels' => $caroussels
        ]);
    }

    public function show(int $id)
    {
        $cartService = new CartService();
        $homeManager = new HomeManager();
        $article = $homeManager->selectOneWithDetails($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['add_article'])) {
                $article = $_POST['add_article'];
                $cartService->add($article);
                header('Location:/telephone/show/' . $id);
            }
        }
        return $this->twig->render('Telephone/show.html.twig', ['article' => $article]);
    }

    public function error404()
    {
        return $this->twig->render('Home/error404.html.twig');
    }
}
