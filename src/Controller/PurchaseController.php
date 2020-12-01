<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\PurchaseManager;

/**
 * Class PurchaseController
 *
 */
class PurchaseController extends AbstractController
{


    /**
     * Display purchase listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $purchaseManager = new PurchaseManager();
            $purchases = $purchaseManager->selectAll();

            return $this->twig->render('Purchase/index.html.twig', ['purchases' => $purchases]);
        } else {
            header('Location:/home/index');
        }
    }

    /**
     * Display purchase informations specified by $id
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
            $purchaseManager = new PurchaseManager();
            $purchase = $purchaseManager->selectOneById($id);
    
            return $this->twig->render('Purchase/show.html.twig', ['purchase' => $purchase]);
        } else {
            header('Location:/home/index');
        }
    }
}
