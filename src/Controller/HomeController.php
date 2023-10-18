<?php

namespace App\Controller;

use App\Repository\ProductRepository;
use App\Repository\PageRepository;
use App\Repository\CollectionRepository;
use App\Repository\CategoryRepository;
use App\Repository\SlidersRepository;
use App\Repository\SettingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{

    private $repoProduct;

    public function __construct(ProductRepository $repoProduct){
        $this->repoProduct = $repoProduct;
    }

    #[Route('/', name: 'app_home')]
    public function index(
        SettingRepository $settingRepo, 
        SlidersRepository $sliderRepo, 
        CollectionRepository $collectionRepo, 
        CategoryRepository $categoryRepo, 
        PageRepository $pageRepo, 
        Request $request): Response
    {
        $session = $request->getSession();

        $data = $settingRepo->findAll();
        $session->set("setting", $data[0]);

        $sliders = $sliderRepo->findAll();

        $collections = $collectionRepo->findBy(['isMega' => false]);
        $megaCollections = $collectionRepo->findBy(['isMega' => true]);
        

        $headerPages = $pageRepo->findBy(['isHead' =>true]);
        $footerPages = $pageRepo->findBy(['isFoot' =>true]);
        $session->set("headerPages", $headerPages);
        $session->set("footerPages", $footerPages);

        $categories = $categoryRepo->findBy(['isMega' => true]);
        $session->set("categories", $categories);
        $session->set("megaCollections", $megaCollections);
        
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'sliders' => $sliders,
            'collections' => $collections,
            'productsBestSeller' => $this->repoProduct->findby(['isBestSeller' => true]),
            'productsNewArrival' => $this->repoProduct->findBy(['isNewArrival' => true]),
            'productsFeatured' => $this->repoProduct->findBy(['isFeatured' => true]),
            'productsSpecialOffer' => $this->repoProduct->findBy(['isSpecialOffer' => true]),
        ]);
    }

    #[Route('/product/{slug}', name: 'app_product_by_slug')]
    public function showProduct(string $slug)
    {
        $product = $this->repoProduct->findOneBy(['slug'=>$slug]);

        if(!$product){
            // error
            return $this->redirectToRoute('app_error');
        }

        return $this->render('product/show_product_by_slug.html.twig', [
            'product'=> $product
        ]);


    }

    #[Route('/product/get/{id}', name: 'app_product_by_id')]
    public function getProductById(string $id)
    {
        $product = $this->repoProduct->findOneBy(['id'=>$id]);

        if(!$product){
            // error
            return $this->json('false');
        }

        return $this->json([
            'id' =>$product->getId(),
            'name' =>$product->getName(),
            'imageUrls' =>$product->getImageUrls(),
            'soldePrice' =>$product->getSoldePrice(),
            'regularPrice' =>$product->getRegularPrice(),
        ]);


    }

    #[Route('/error', name: 'app_error')]
    public function errorPage()
    {
        return $this->render('page/not-fount.html.twig', [
            'controller_name' => 'PageController'
        ]);

    }
}
