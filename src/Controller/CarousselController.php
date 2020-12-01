<?php

namespace App\Controller;

use App\Model\CarousselManager;

/**
 * Class ImageController
 */
class CarousselController extends AbstractController
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
            $carousselManager = new CarousselManager();
            $caroussels = $carousselManager->selectAll();
            return $this->twig->render('Caroussel/index.html.twig', ['caroussels' => $caroussels]);
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
            $carousselManager = new CarousselManager();
            $caroussel = $carousselManager->selectOneWithDetails($id);
            return $this->twig->render('Caroussel/show.html.twig', ['caroussel' => $caroussel]);
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
            $carousselManager = new CarousselManager();
            $caroussel = $carousselManager->selectOneWithDetails($id);
        
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $caroussel = [
                    'id' => $id,
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                ];
                $carousselManager->update($caroussel);
                header('Location:/caroussel/index/');
            }
            return $this->twig->render('Caroussel/edit.html.twig', [
                'caroussel' => $caroussel
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
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $carousselManager = new CarousselManager();
                $caroussel = [
                    'name' => $_POST['name'],
                    'url' => $_POST['url'],
                ];
                $carousselManager->insert($caroussel);
                header('Location:/caroussel/index/');
            }
            return $this->twig->render('Caroussel/add.html.twig');
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
            $carousselManager = new CarousselManager();
            $carousselManager->delete($id);
            header('Location:/caroussel/index');
        } else {
            header('Location:/home/index');
        }
    }
}
