<?php

namespace App\Controller;

use App\Model\UserManager;
use App\Model\PurchaseManager;
use App\Model\InvoiceManager;

class SecurityController extends AbstractController
{
    public function login()
    {
        $userManager = new UserManager();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['email']) && !empty($_POST['password'])) {
                $user = $userManager->search($_POST['email']);
                if ($user) {
                    if ($user->password === md5($_POST['password'])) {
                        $infosUser = $userManager->selectOneById($user->id);
                        $_SESSION['firstname'] = $infosUser['firstname'];
                        $_SESSION['lastname'] = $infosUser['lastname'];
                        $_SESSION['name'] = $user->email;
                        $_SESSION['id'] = $user->id;
                        $_SESSION['role'] = $user->role_id;
                        header('Location:/security/show/'.$_SESSION['id']);
                    } else {
                        $error = 'Mot de passe incorrect !';
                    }
                } else {
                    $error = 'Email non valide ';
                }
            } else {
                $error = 'Tous les champs sont obligatoires !';
            }
        }
        return $this->twig->render('UserLogin/UserLogin.html.twig', [
            'error' => $error
        ]);
    }

    public function register()
    {
        $userManager = new UserManager();
        $error = null;
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['firstname']) &&
                !empty($_POST['lastname']) &&
                !empty($_POST['email']) &&
                !empty($_POST['address']) &&
                !empty($_POST['password']) &&
                !empty($_POST['password2'])) {
                $user = $userManager->search($_POST['email']);
                if ($user) {
                    $error = 'Email déjà existant';
                }
                if ($_POST['password'] != $_POST['password2']) {
                    $error = 'Les mots de passe sont différents';
                }
                if ($error === null) {
                    $user = [
                        'firstname' => $_POST['firstname'],
                        'lastname' => $_POST['lastname'],
                        'email' => $_POST['email'],
                        'address' => $_POST['address'],
                        'password' => md5($_POST['password']),
                        'role_id' => 2
                    ];
                    $idUser = $userManager->insert($user);
                    if ($idUser) {
                        $_SESSION['name'] = $user['email'];
                        $_SESSION['id'] = $idUser;
                        header('Location:/');
                    }
                }
            }
        }
        return $this->twig->render('UserLogin/register.html.twig', [
            'error' => $error
        ]);
    }

    
    public function edit(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['id'] == $id) {
            $userManager = new UserManager();
            $user = $userManager->selectOneWithDetails($id);
            
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $user = [
                    'id' => $id,
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'password' => md5($_POST['password']),
                    'address' => $_POST['address'],
                ];
                
                $userManager->updateUser($user);
                header('Location:/security/show/'. $id);
            }
    
            return $this->twig->render('UserLogin/edit.html.twig', [
                'user' => $user,
            ]);
        } else {
            header('Location:/home/error404');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location:/');
    }
    
    public function show(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['id'] == $id) {
            $userManager = new UserManager();
            $user = $userManager->selectOneWithDetails($id);
            $purchaseManager = new PurchaseManager();
            $purchases = $purchaseManager->selectAllByUser($id);
            $invoiceManager = new InvoiceManager();
            $invoiceList = [];
            $invoiceByPurchase = [];
            foreach ($purchases as $purchase) {
                $invoices = $invoiceManager->selectAllByPurchase($purchase['id']);
                foreach ($invoices as $invoice) {
                    $invoiceByPurchase[] = $invoice;
                }
            }
            return $this->twig->render('UserLogin/show.html.twig', [
                'user' => $user,
                'purchases' => $purchases,
                'invoices' => $invoiceByPurchase
                ]);
            } else {
                header('Location:/home/error404');
            }
        }
}
