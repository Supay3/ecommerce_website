<?php


namespace App\Services\Shop\Order;


use App\Entity\Shop\Product\Product;
use App\Exception\Shop\Cart\CartEmptyException;
use App\Exception\Shop\Cart\TooMuchInCartException;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService
{
    /**
     * @var SessionInterface
     */
    private SessionInterface $session;

    public function __construct (SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Add an item to cart
     *
     * @param Product $product The product to add
     * @throws TooMuchInCartException If the product doesn't have enough stock
     */
    public function addToCart (Product $product): void
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$product->getId()])) {
            if (!($cart[$product->getId()] >= $product->getStock())) {
                $cart[$product->getId()]++;
            } else {
                throw new TooMuchInCartException();
            }
        } else {
            $cart[$product->getId()] = 1;
        }

        $this->session->set('cart', $cart);
    }

    /**
     * Add one or more item to the cart
     *
     * @param Product $product The product to add
     * @param int $quantity The quantity of the product to add
     * @throws TooMuchInCartException If the product doesn't have enough stock
     */
    public function addItemAndQuantityToCart(Product $product, int $quantity)
    {
        $cart = $this->session->get('cart', []);
        if (!empty($cart[$product->getId()])) {
            if ($cart[$product->getId()] < $product->getStock() && ($cart[$product->getId()] + $quantity) <= $product->getStock()) {
                $cart[$product->getId()] = $cart[$product->getId()] + $quantity;
            } else {
                throw new TooMuchInCartException();
            }
        } else {
            $cart[$product->getId()] = $quantity;
        }
        $this->session->set('cart', $cart);
    }

    /**
     * Removes one item from the cart, removes the item if his quantity is equal to one
     *
     * @param int $id
     */
    public function removeFromCart (int $id)
    {
        $cart = $this->session->get('cart', []);

        if (!empty($cart[$id])) {
            if ($cart[$id] > 1) {
                $cart[$id]--;
            } else {
                unset($cart[$id]);
            }
        }
        $this->session->set('cart', $cart);
    }

    /**
     * Removes the all cart
     */
    public function removeAllCart ()
    {
        $this->session->remove('cart');
    }

    /**
     * Returns an array with all the items in the cart
     *
     * @param ServiceEntityRepository $entityRepository The repository of the Products
     * @param string|null $locale The current locale, if null just returns the product
     * @return array The array of cartProducts
     */
    public function getFullCart (ServiceEntityRepository $entityRepository, string $locale = null): array
    {
        $cartProducts = [];
        foreach ($this->session->get('cart', []) as $cartProduct => $quantity) {
            $currentProduct = $entityRepository->find($cartProduct);
            if ($locale) {
                $translation = null;
                foreach ($currentProduct->getProductTranslations() as $productTranslation) {
                    if ($productTranslation->getLocale()->getCode() === $locale) {
                        $translation = $productTranslation;
                    }
                }
            }
            $cartProducts[] = [
                'item' => $currentProduct,
                'translation' => $translation ?? '',
                'quantity' => $quantity
            ];
        }
        return $cartProducts;
    }

    /**
     * Returns the total price of the cart
     *
     * @param array|null $cartProducts The cartProducts formatted by the getFullCart() method
     * @return float The total price of the cart
     */
    public function getTotalFromCart (?array $cartProducts): float
    {
        $total = 0;
        if ($cartProducts) {
            foreach ($cartProducts as $cartProduct) {
                $total += ($cartProduct['item']->getPrice() * $cartProduct['quantity']);
            }
        }
        return $total;
    }

    /**
     * Returns the cart as it is in the session, $id => $quantity
     *
     * @return array|null The array of the cart
     */
    public function getCart (): ?array
    {
        return $this->session->get('cart', []);
    }

    /**
     * Checks if the cart is set and has products inside
     *
     * @throws CartEmptyException If the cart is empty or is not set in session
     */
    public function checkCart()
    {
        $cart = $this->session->get('cart');
        if ($cart !== null && !empty($cart)) {
            return;
        }
        throw new CartEmptyException();
    }
}