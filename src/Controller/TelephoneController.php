<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CommentManager;
use App\Model\ModelManager;
use App\Model\BrandManager;
use App\Model\ColorManager;
use App\Service\CartService;
use App\Service\SearchService;

/**
 * Class TelephoneController
 */
class TelephoneController extends AbstractController
{


    /**
     * Display article listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        $articleManager = new ArticleManager();
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAllWithImage();
        $modelManager = new ModelManager();
        $models = $modelManager->selectAll();
        $brandManager = new BrandManager();
        $brands = $brandManager->selectAll();
        $colorManager = new ColorManager();
        $colors = $colorManager->selectAll();
        $model = "model";
        $brand = "brand";
        $color = "color";
        if (!empty($_POST) && isset($_POST['model']) && isset($_POST['brand']) && isset($_POST['color'])) {
            $articles = $articleManager->selectAllWithThreeFilter($model, $brand, $color);
        } elseif (!empty($_POST) && isset($_POST['model']) && isset($_POST['brand'])) {
            $articles = $articleManager->selectAllWithTwoFilter($model, $brand);
        } elseif (!empty($_POST) && isset($_POST['model']) && isset($_POST['color'])) {
            $articles = $articleManager->selectAllWithTwoFilter($model, $color);
        } elseif (!empty($_POST) && isset($_POST['brand']) && isset($_POST['color'])) {
            $articles = $articleManager->selectAllWithTwoFilter($brand, $color);
        } elseif (!empty($_POST) && isset($_POST['model'])) {
            $articles = $articleManager->selectByFilter($model);
        } elseif (!empty($_POST) && isset($_POST['brand'])) {
            $articles = $articleManager->selectByFilter($brand);
        } elseif (!empty($_POST) && isset($_POST['color'])) {
            $articles = $articleManager->selectByFilter($color);
        }
        
        if (!empty($_POST) && isset($_POST['search'])) {
            $articles = $articleManager->search($_POST['search']);
            if (empty($articles)) {
                $articles = $articleManager->searchByColor($_POST['search']);
                if (empty($articles)) {
                    $articles = $articleManager->searchByBrand($_POST['search']);
                }
            }
        }

        $articleNb='';
        if (!empty($_POST)) {
            $articleNb = count($articles);
        }
        return $this->twig->render('Telephone/index.html.twig', [
            'articles' => $articles,
            'models' => $models,
            'brands' => $brands,
            'colors' => $colors,
            'articleNb' => $articleNb
        ]);
    }
    /**
     * Display article informations specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(int $id)
    {
        $cartService = new CartService();
        $articleManager = new ArticleManager();
        $commentManager = new CommentManager();
        $article = $articleManager->selectOneWithDetails($id);
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['add_article'])) {
                $article = $_POST['add_article'];
                $cartService->add($article);
                header('Location:/telephone/show/' . $id);
            }
        }

        if (!empty($_POST) && isset($_POST['review'])) {
            $comment = [
                'article_id' => $id,
                'user_id' => $_SESSION['id'],
                'review' => $_POST['review']
            ];
            $commentManager->insert($comment);
        }
        $comments = $commentManager->selectAllById($id);
        return $this->twig->render('Telephone/show.html.twig', [
            'article' => $article,
            'comments' => $comments
        ]);
    }
}
