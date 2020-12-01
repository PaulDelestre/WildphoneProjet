<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\BrandManager;

/**
 * Class BrandController
 *
 */
class BrandController extends AbstractController
{


    /**
     * Display brand listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $brandManager = new BrandManager();
            $brands = $brandManager->selectAll();
    
            return $this->twig->render('Brand/index.html.twig', ['brands' => $brands]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display brand informations specified by $id
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
            $brandManager = new BrandManager();
            $brand = $brandManager->selectOneById($id);
    
            return $this->twig->render('Brand/show.html.twig', ['brand' => $brand]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display brand edition page specified by $id
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
            $brandManager = new BrandManager();
            $brand = $brandManager->selectOneById($id);
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $brand['name'] = $_POST['name'];
                $brandManager->update($brand);
                header('Location:/brand/index/');
            }
    
            return $this->twig->render('Brand/edit.html.twig', ['brand' => $brand]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display brand creation page
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
                $brandManager = new BrandManager();
                $brand = [
                    'name' => $_POST['name'],
                ];
                $brandManager->insert($brand);
                header('Location:/brand/index/');
            }
    
            return $this->twig->render('Brand/add.html.twig');
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle brand deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $brandManager = new BrandManager();
            $brandManager->delete($id);
            header('Location:/brand/index');
        } else {
            header('Location:/home/index');
        }
    }
}
