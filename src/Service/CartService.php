<?php

namespace App\Service;

use App\Model\ArticleManager;
use App\Model\InvoiceManager;
use App\Model\PurchaseManager;
use Stripe\Stripe;

class CartService
{
    public function add($article)
    {
        if (!empty($_SESSION['cart'][$article])) {
            $_SESSION['cart'][$article]++;
        } else {
            $_SESSION['cart'][$article] = 1;
        }
        $_SESSION['count'] = $this->countArticle();
        header('Location:/telephone/index');
    }

    
    public function delete($article)
    {
        $cart = $_SESSION['cart'];
        if (!empty($cart[$article])) {
            unset($cart[$article]);
        }
        $_SESSION['cart'] = $cart;
        $_SESSION['count'] = $this->countArticle();
        header('Location:/panier/index');
    }

    public function cartInfos()
    {
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $cartInfos = [];
            $articleManager = new ArticleManager();
            foreach ($cart as $id => $qty) {
                $infosArticle = $articleManager->selectOneWithDetails($id);
                $infosArticle['qty'] = $qty;
                $cartInfos[] = $infosArticle;
            }
            return $cartInfos;
        }
        return false;
    }

    public function totalCart()
    {
        $total = 0;
        if ($this->cartInfos() != false) {
            foreach ($this->cartInfos() as $item) {
                $total += $item['price'] * $item['qty'];
            }
            return $total;
        }
        return $total;
    }

    public function countArticle()
    {
        $total = 0;
        if ($this->cartInfos() != false) {
            foreach ($this->cartInfos() as $item) {
                $total += $item['qty'];
            }
            return $total;
        }
        return $total;
    }

    public function update(array $array)
    {
        $articleManager = new ArticleManager();
        for ($i = 0; $i < count($array['id']); $i++) {
            $article = $articleManager->selectOneWithDetails($array['id'][$i]);
            foreach ($_SESSION['cart'] as $id => $qty) {
                $newCount = $article['quantity'] - intval($array['qty'][$i]);
                if ($newCount >= 0) {
                    $_SESSION['cart'][$array['id'][$i]] = $array['qty'][$i];
                } else {
                    $_SESSION['flash_message'] = ["Article " . $article['name'] .
                    " est seulement disponible en " . $article['quantity'] .
                    " exemplaire !"];
                    header('Location:/panier/index/');
                }
            }
        }
        header('Location:/panier/index');
    }

    public function payment($infos)
    {
        $stripe = \Stripe\Stripe::setApiKey(API_KEY);
        $articleManager = new ArticleManager();
        $invoiceManager = new InvoiceManager();

        $purchaseManager = new PurchaseManager();
        $data = [
            'user_id' => $_SESSION['id'],
            'total' => $this->totalCart(),
            'created_at' => date("Y-m-d")
        ];
        $idCommand = $purchaseManager->insert($data);
        $articles = $_SESSION['cart'];
        foreach ($articles as $articleId => $quantity) {
            $data2 = [
                'purchase_id' => $idCommand,
                'article_id' => $articleId,
                'quantity' => $quantity,
                'total' => $articleManager->selectOneById($articleId)['price'] * $quantity
            ];
            $invoiceManager->insert($data2);
        }
        try {
            $data = [
                'source' => $_POST['stripeToken'],
                'description' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $customer = \Stripe\Customer::create($data);
            $charge = \Stripe\Charge::create([
                'amount' => $this->totalCart() * 100,
                'currency' => 'eur',
                'description' => 'Example charge',
                'customer' => $customer->id,
                'statement_descriptor' => 'Custom descriptor',
            ]);

            unset($_SESSION['cart']);
            unset($_SESSION['count']);
            $_SESSION['transaction'] = $charge->receipt_url;

            header('Location:/panier/success');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $e->getError();
            var_dump($e);
        }
    }
}
