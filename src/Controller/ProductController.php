<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Routing\Attribute\Route;
use Doctrine\ORM\EntityManager;

class ProductController extends AbstractController
{
    #[Route('/products/new', 'products_new')]
    public function new(EntityManager $em): string
    {
        $product = new Product();
        $product
            ->setName(name: "sIgTKZNDZrr")
            ->setPrice(76.24);

        $em->persist(entity: $product);
        $em->flush();

        return $this->twig->render("products/new.html.twig", [
            'product' => $product
        ]);
    }

    #[Route('/products/list', 'products_list')]
    public function list(ProductRepository $productRepository): string
    {
        return $this->twig->render('products/list.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    }

    #[Route('/products/{id}', 'product_show', 'GET')]
    public function show(ProductRepository $productRepository, int $id): string
    {
        $product = $productRepository->find($id);

        if (!$product) {
            return 'Produit non trouvé';
        }

        return $this->twig->render('products/show.html.twig', [
            'product' => $product
        ]);
    }
}