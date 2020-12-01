<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\ImageManager;
use App\Model\ColorManager;
use App\Model\ModelManager;
use App\Model\BrandManager;

/**
 * Class ArticleController
 */
class ArticleController extends AbstractController
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
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $articleManager = new ArticleManager();
            $articles = $articleManager->selectAllWithImage();
            return $this->twig->render('Article/index.html.twig', ['articles' => $articles]);
        } else {
            header('Location:/home/index');
        }
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
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $articleManager = new ArticleManager();
            $article = $articleManager->selectOneWithDetails($id);
            //var_dump($article);die;
            return $this->twig->render('Article/show.html.twig', ['article' => $article]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display article edition page specified by $id
     *
     * @param int $id
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function edit(int $id)
    {
    
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $articleManager = new ArticleManager();
            $article = $articleManager->selectOneWithDetails($id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $article = [
                    'id' => $id,
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'description' => $_POST['description'],
                    'quantity' => $_POST['quantity'],
                    'color_id' => $_POST['color_id'],
                    'brand_id' => $_POST['brand_id'],
                    'model_id' => $_POST['model_id']
                ];
                if (!empty($_POST["image"])) {
                    $image = [
                        'url'=> $_POST['image'],
                        'article_id'=> $id
                    ];
                    $imageManager = new ImageManager();
                    $imageManager->insert($image);
                }
                $articleManager->update($article);
                header('Location:/article/show/' . $id);
            }

            $colorManager = new ColorManager();
            $colors = $colorManager->selectAll();
            $brandManager = new BrandManager();
            $brands = $brandManager->selectAll();
            $modelManager = new ModelManager();
            $models = $modelManager->selectAll();
            return $this->twig->render('Article/edit.html.twig', [
                'article' => $article,
                'colors' => $colors,
                'brands' => $brands,
                'models' => $models
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display specie creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $articleManager = new ArticleManager();
            $articles = $articleManager->selectAll();
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $articleManager = new ArticleManager();
                $article = [
                    'name' => $_POST['name'],
                    'price' => $_POST['price'],
                    'description' => $_POST['description'],
                    'quantity' => $_POST['quantity'],
                    'color_id' => $_POST['color_id'],
                    'brand_id' => $_POST['brand_id'],
                    'model_id' => $_POST['model_id']
                ];
                $articleManager->insert($article);
                header('Location:/article/index/');
            }
            $colorManager = new ColorManager();
            $colors = $colorManager->selectAll();
            $brandManager = new BrandManager();
            $brands = $brandManager->selectAll();
            $modelManager = new ModelManager();
            $models = $modelManager->selectAll();
            return $this->twig->render('Article/add.html.twig', [
                'articles' => $articles,
                'colors' => $colors,
                'brands' => $brands,
                'models' => $models
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle article deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $articleManager = new ArticleManager();
            $articleManager->delete($id);
            header('Location:/article/index');
        } else {
            header('Location:/home/index');
        }
    }
}
