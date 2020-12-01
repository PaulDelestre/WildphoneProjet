<?php

namespace App\Controller;

use App\Model\ImageManager;
use App\Model\ArticleManager;

/**
 * Class ImageController
 */
class ImageController extends AbstractController
{


    /**
     * Display image listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $imageManager = new ImageManager();
            $images = $imageManager->selectAll();
            return $this->twig->render('Image/index.html.twig', ['images' => $images]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display image informations specified by $id
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
            $imageManager = new ImageManager();
            $image = $imageManager->selectOneWithDetails($id);
            return $this->twig->render('Image/show.html.twig', ['image' => $image]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display image edition page specified by $id
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
            $imageManager = new ImageManager();
            $image = $imageManager->selectOneWithDetails($id);
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $image = [
                    'id' => $id,
                    'url' => $_POST['url'],
                    'article_id' => $_POST['article_id'],
                ];
                $imageManager->update($image);
                header('Location:/image/index/');
            }
            $articleManager = new ArticleManager();
            $articles = $articleManager->selectAll();
            return $this->twig->render('Image/edit.html.twig', [
                'image' => $image,
                'articles' => $articles
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
            $imageManager = new ImageManager();
            $images = $imageManager->selectAll();

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $imageManager = new ImageManager();
                $image = [
                    'url' => $_POST['url'],
                    'article_id' => $_POST['article_id'],
                ];
                $imageManager->insert($image);
                header('Location:/image/index/');
            }
            $articleManager = new ArticleManager();
            $articles = $articleManager->selectAll();
            return $this->twig->render('Image/add.html.twig', [
                'images' => $images,
                'articles' => $articles
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle image deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $imageManager = new ImageManager();
            $imageManager->delete($id);
            header('Location:/image/index');
        } else {
            header('Location:/home/index');
        }
    }
}
