<?php

namespace App\Controller\Shop;

use App\Controller\RouteName;
use App\Entity\Shop\Locale;
use App\Entity\Shop\Product\Product;
use App\Entity\Shop\ProductSearch;
use App\Form\Shop\ProductSearchType;
use App\Repository\Shop\LocaleRepository;
use App\Repository\Shop\Product\ProductCategoryTranslationRepository;
use App\Repository\Shop\Product\ProductTranslationRepository;
use App\Repository\Shop\Product\ProductTypeTranslationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/shop')]
class ShopController extends AbstractController
{
    private LocaleRepository $localeRepository;

    public function __construct(LocaleRepository $localeRepository)
    {
        $this->localeRepository = $localeRepository;
    }

    #[Route('/', name: RouteName::SHOP_INDEX)]
    public function index(
        Request $request,
        ProductCategoryTranslationRepository $productCategoryTranslationRepository,
        ProductTypeTranslationRepository $productTypeTranslationRepository,
        ProductTranslationRepository $productTranslationRepository
    ): Response
    {
        $locale = $request->getLocale();
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $locale)) {
            throw new NotFoundHttpException();
        }
        $search = new ProductSearch();
        $form = $this->createForm(ProductSearchType::class, $search);
        $form->handleRequest($request);
        return $this->render('shop/index.html.twig', [
            'categoriesTranslation' => $productCategoryTranslationRepository->findAllByLocale($locale, $search),
            'productTypesTranslation' => $productTypeTranslationRepository->findAllByLocale($locale, $search),
            'productsTranslation' => $productTranslationRepository->findAllEnabled($locale, $search),
            'form' => $form->createView(),
        ]);
    }

    #[Route('/product/{id}-{slug}', name: RouteName::SHOP_SHOW, requirements: ['slug'=> '[a-z0-9\-]*'])]
    public function show(
        Product $product,
        string $slug,
        Request $request,
        ProductTranslationRepository $productTranslationRepository
    ): Response
    {
        $locale = $request->getLocale();
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $locale)) {
            throw new NotFoundHttpException();
        }
        if ($product->getSlug() !== $slug) {
            return $this->redirectToRoute(RouteName::SHOP_SHOW, [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ], 301);
        }
        $currentLocale = $this->localeRepository->findOneBy(['code' => $locale]);
        return $this->render('shop/show.html.twig', [
            'product' => $product,
            'productTranslation' => $productTranslationRepository->findOneBy(['locale' => $currentLocale, 'product' => $product->getId()]),
        ]);
    }
}
