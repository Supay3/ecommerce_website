<?php

namespace App\Controller\Shop;

use App\Controller\RouteName;
use App\Entity\Shop\Product\Product;
use App\Exception\Shop\Cart\TooMuchInCartException;
use App\Repository\Shop\LocaleRepository;
use App\Repository\Shop\Product\ProductRepository;
use App\Services\Shop\Order\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/{_locale}/shop/cart')]
class CartController extends AbstractController
{
    /**
     * @var CartService
     */
    private CartService $cartService;
    private LocaleRepository $localeRepository;

    public function __construct(CartService $cartService, LocaleRepository $localeRepository)
    {
        $this->cartService = $cartService;
        $this->localeRepository = $localeRepository;
    }

    #[Route('/', name: RouteName::CART_INDEX)]
    public function index(ProductRepository $productRepository, Request $request): Response
    {
        $locale = $request->getLocale();
        if (!RouteName::checkAuthorizedLocales($this->localeRepository->findAll(), $locale)) {
            throw new NotFoundHttpException();
        }
        $products = $this->cartService->getFullCart($productRepository, $locale);
        $total = $this->cartService->getTotalFromCart($products);
        return $this->render('shop/cart/index.html.twig', [
            'products' => $products,
            'total' => $total,
        ]);
    }

    #[Route('/add/{id}', name: RouteName::CART_ADD, methods: ['POST'])]
    public function addToCart(Product $product, Request $request): RedirectResponse
    {
        try {
            if (!$product->getStock() <= 0 && $this->isCsrfTokenValid('add' . $product->getId(), $request->request->get('_token'))) {
                $quantity = (int)$request->request->get('quantity');
                if (!$quantity <= 0 && $quantity <= $product->getStock()) {
                    $this->cartService->addItemAndQuantityToCart($product, $quantity);
                    return $this->redirectToRoute(RouteName::CART_INDEX);
                } else {
                    throw new TooMuchInCartException();
                }
            } else {
                throw new NotFoundHttpException();
            }
        } catch (TooMuchInCartException $e) {
            $this->addFlash($e::ERROR_NAME, $e->errorMessage());
            return $this->redirectToRoute(RouteName::SHOP_SHOW, [
                'id' => $product->getId(),
                'slug' => $product->getSlug(),
            ]);
        }
    }

    #[Route('/add_from_index/{id}', name: RouteName::CART_ADD_INDEX, methods: ['POST'])]
    public function addToCartFromIndex(Product $product, Request $request): JsonResponse
    {
        try {
            if (!$this->isCsrfTokenValid('add-cart' . $product->getId(), $request->getContent())) {
                throw new NotFoundHttpException();
            }
            if (!$product->getStock() <= 0) {
                $this->cartService->addToCart($product);
                return new JsonResponse('Produit bien ajouté au panier');
            } else {
                throw new NotFoundHttpException('Ce produit n\'a plus de stock et ne peut donc pas être ajouté à votre panier');
            }
        } catch (TooMuchInCartException $e) {
            return new JsonResponse($e->errorMessage(), 300);
        }
    }

    #[Route('/remove/{id}', name: RouteName::CART_REMOVE, methods: ['POST'])]
    public function removeFromCart(Product $product, Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('cart-remove' . $product->getId(), $request->request->get('_token'))) {
            throw new NotFoundHttpException();
        }
        $this->cartService->removeFromCart($product->getId());
        $referer = $request->headers->get('referer');
        if ($referer !== null) {
            return $this->redirect($referer);
        }

        return $this->redirectToRoute(RouteName::CART_INDEX);
    }

    #[Route('/remove-all', name: RouteName::CART_REMOVE_ALL, methods: ['POST'])]
    public function removeAllCart(Request $request): RedirectResponse
    {
        if (!$this->isCsrfTokenValid('cart-remove-all', $request->request->get('_token'))) {
            throw new NotFoundHttpException();
        }
        $this->cartService->removeAllCart();
        $referer = $request->headers->get('referer');
        if ($referer !== null) {
            return $this->redirect($referer);
        }
        return $this->redirectToRoute(RouteName::CART_INDEX);
    }
}
