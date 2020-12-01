<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\ColorManager;

/**
 * Class ColorController
 *
 */
class ColorController extends AbstractController
{


    /**
     * Display color listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $colorManager = new ColorManager();
            $colors = $colorManager->selectAll();

            return $this->twig->render('Color/index.html.twig', ['colors' => $colors]);
        } else {
            header('Location:/home/index');
        }
    }

    /**
     * Display color informations specified by $id
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
            $colorManager = new ColorManager();
            $color = $colorManager->selectOneById($id);
    
            return $this->twig->render('Color/show.html.twig', ['color' => $color]);
        } else {
            header('Location:/home/index');
        }
    }

    /**
     * Display color edition page specified by $id
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
            $colorManager = new ColorManager();
            $color = $colorManager->selectOneById($id);
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $color['name'] = $_POST['name'];
                $colorManager->update($color);
                header('Location:/color/index/');
            }
            return $this->twig->render('Color/edit.html.twig', ['color' => $color]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display color creation page
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
                $colorManager = new ColorManager();
                $color = [
                    'name' => $_POST['name'],
                ];
                $id = $colorManager->insert($color);
                header('Location:/color/show/' . $id);
            }
            return $this->twig->render('Color/add.html.twig');
        } else {
            header('Location:/home/index');
        }
    }

    /**
     * Handle color deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $colorManager = new ColorManager();
            $colorManager->delete($id);
            header('Location:/color/index');
        } else {
            header('Location:/home/index');
        }
    }
}
