<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ModelManager;

/**
 * Class ModelController
 *
 */
class ModelController extends AbstractController
{


    /**
     * Display model listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $modelManager = new ModelManager();
            $models = $modelManager->selectAll();

            return $this->twig->render('Model/index.html.twig', ['models' => $models]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display model informations specified by $id
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
            $modelManager = new ModelManager();
            $model = $modelManager->selectOneById($id);

            return $this->twig->render('Model/show.html.twig', ['model' => $model]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display model edition page specified by $id
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
            $modelManager = new ModelManager();
            $model = $modelManager->selectOneById($id);

            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $model['name'] = $_POST['name'];
                $modelManager->update($model);
                header('Location:/model/index/');
            }

            return $this->twig->render('Model/edit.html.twig', ['model' => $model]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display model creation page
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
                $modelManager = new ModelManager();
                $model = [
                    'name' => $_POST['name'],
                ];
                $id = $modelManager->insert($model);
                header('Location:/model/show/' . $id);
            }

            return $this->twig->render('Model/add.html.twig');
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle model deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $modelManager = new ModelManager();
            $modelManager->delete($id);
            header('Location:/model/index');
        } else {
            header('Location:/home/index');
        }
    }
}
