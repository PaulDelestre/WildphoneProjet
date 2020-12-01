<?php
/**
 * Created by PhpStorm.
 * User: root
 * Date: 11/10/17
 * Time: 16:07
 * PHP version 7
 */

namespace App\Controller;

use App\Model\InvoiceManager;

/**
 * Class InvoiceController
 *
 */
class InvoiceController extends AbstractController
{


    /**
     * Display invoice listing
     *
     * @return string
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function index()
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $invoiceManager = new InvoiceManager();
            $invoices = $invoiceManager->selectAll();
            return $this->twig->render('Invoice/index.html.twig', ['invoices' => $invoices]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display invoice informations specified by $id
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
            $invoiceManager = new InvoiceManager();
            $invoice = $invoiceManager->selectOneById($id);
            return $this->twig->render('Invoice/show.html.twig', ['invoice' => $invoice]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display invoice edition page specified by $id
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
            $invoiceManager = new InvoiceManager();
            $invoice = $invoiceManager->selectOneById($id);
    
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $invoice = [
                    'id' => $id,
                    'article_id' => $_POST['article_id'],
                    'purchase_id' => $_POST['purchase_id'],
                    'quantity' => $_POST['quantity'],
                    'total' => $_POST['total'],
                ];
    
                $invoiceManager->update($invoice);
                header('Location:/invoice/index/');
            }
    
            return $this->twig->render('Invoice/edit.html.twig', [
                'invoice' => $invoice,
            ]);
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Display invoice creation page
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
                $invoiceManager = new InvoiceManager();
                $invoice = [
                    'quantity' => $_POST['quantity'],
                    'total' => $_POST['total'],
                    'article_id' => $_POST['article_id'],
                    'purchase_id' => $_POST['purchase_id'],
                ];
                $id = $invoiceManager->insert($invoice);
                header('Location:/invoice/show/' . $id);
            }
    
            return $this->twig->render('Invoice/add.html.twig');
        } else {
            header('Location:/home/index');
        }
    }


    /**
     * Handle invoice deletion
     *
     * @param int $id
     */
    public function delete(int $id)
    {
        if (!empty($_SESSION) && $_SESSION['role'] == 1) {
            $invoiceManager = new InvoiceManager();
            $invoiceManager->delete($id);
            header('Location:/invoice/index');
        } else {
            header('Location:/home/index');
        }
    }
}
