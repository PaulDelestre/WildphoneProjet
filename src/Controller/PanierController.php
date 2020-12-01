<?php
/**
 * Created by PhpStorm.
 * User: aurelwcs
 * Date: 08/04/19
 * Time: 18:40
 */

namespace App\Controller;

use App\Service\CartService;
use App\Model\ArticleManager;
use App\Model\UserManager;

class PanierController extends AbstractController
{

    public function index()
    {
        $cartService = new CartService();
        $userManager = new UserManager();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['update_cart'])) {
                $cartService->update($_POST);
            }
        }
        if (isset($_POST['payment'])) {
            if (!empty($_POST['name']) && !empty($_POST['firstname']) && !empty($_POST['address'])) {
                $cartService->payment($_POST);
            } else {
                $_SESSION['flash_message'] = ["Tous les champs sont obligatoires !"];
                header('Location:/panier/index');
            }
        }
        if (!empty($_SESSION) && isset($_SESSION['id'])) {
            $user = $userManager->selectOneById($_SESSION['id']);
            return $this->twig->render('Panier/index.html.twig', [
                'email' => $_SESSION['name'],
                'firstname' => $user['firstname'],
                'lastname' => $user['lastname'],
                'address' => $user['address'],
                'cartInfos' => $cartService->cartInfos() ? $cartService->cartInfos() : null,
                'total' => $cartService->cartInfos() ? $cartService->totalCart() : null
            ]);
        } else {
            return $this->twig->render('Panier/index.html.twig', [
                'cartInfos' => $cartService->cartInfos() ? $cartService->cartInfos() : null,
                'total' => $cartService->cartInfos() ? $cartService->totalCart() : null
            ]);
        }
    }

    public function success()
    {
        if (!empty($_SESSION) && isset($_SESSION['transaction'])) {
            return $this->twig->render('Panier/success.html.twig');
        } else {
            header('Location:/panier/index');
        }
    }

    public function deleteArticle($id)
    {
        $cartService = new CartService();
        $cartService->delete($id);
        header('Location:/panier/index');
    }
}
