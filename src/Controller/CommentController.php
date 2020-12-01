<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\CommentManager;

/**
 * Class CommentController
 *
 */
class CommentController extends AbstractController
{


    /**
     * Display comment listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $commentManager = new CommentManager();
            $comments = $commentManager->selectAll();
            return $this->twig->render('Comment/index.html.twig', ['comments' => $comments]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display comment informations specified by $id
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
            $commentManager = new CommentManager();
            $comment = $commentManager->selectOneById($id);
    
            return $this->twig->render('Comment/show.html.twig', ['comment' => $comment]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display comment edition page specified by $id
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
            $commentManager = new CommentManager();
            $comment = $commentManager->selectOneById($id);
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $comment = [
                    'id' => $id,
                    'review' => $_POST['review'],
                    'article_id' => $_POST['article_id'],
                    'user_id' => $_POST['user_id']
                ];
                $commentManager->update($comment);
                header('Location:/comment/index/');
            }
    
            return $this->twig->render('Comment/edit.html.twig', ['comment' => $comment]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display comment creation page
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function add()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $commentManager = new CommentManager();
                $comment = [
                    'review' => $_POST['review'],
                    'article_id' => $_POST['article_id'],
                    'user_id' => $_POST['user_id']
                ];
                $id = $commentManager->insert($comment);
                header('Location:/comment/show/' . $id);
            }
    
            return $this->twig->render('Comment/add.html.twig');
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle comment deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $commentManager = new CommentManager();
            $commentManager->delete($id);
            header('Location:/comment/index');
        } else {
            header('Location:/home/index');
        }
    }
}
