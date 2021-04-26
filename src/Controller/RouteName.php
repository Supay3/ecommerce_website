<?php


namespace App\Controller;


class RouteName
{
    // Routes for administration
    public const ADMIN_INDEX = 'admin_index';

    // Routes for Users
    public const APP_LOGIN = 'app_login';
    public const APP_LOGOUT = 'app_logout';
    public const USER_PROFILE_INDEX = 'user_profile_index';
    public const USER_PROFILE_ADD_ADDRESS = 'user_profile_add_address';
    public const USER_PROFILE_EDIT_ADDRESS = 'user_profile_edit_address';
    public const USER_PROFILE_DELETE_ADDRESS = 'user_profile_delete_address';
    public const USER_CREATE_ACCOUNT = 'user_create_account';
    public const USER_ORDER_INDEX = 'user_order_index';
    public const USER_ORDER = 'user_order';

    // Routes for the Shop
    public const SHOP_INDEX = 'shop_index';
    public const SHOP_SHOW = 'shop_show';

    // Routes for the checkout
    public const CHECKOUT_CHOOSE_ACCOUNT = 'checkout_choose_account';
    public const CHECKOUT_ADDRESS = 'checkout_address';
    public const CHECKOUT_SHIPMENT = 'checkout_shipment';
    public const CHECKOUT_SUMMARY = 'checkout_summary';
    public const CHECKOUT_VALIDATION = 'checkout_validation';
    public const CHECKOUT_PAY = 'checkout_pay';
    public const CHECKOUT_REMOVE_BILLING_ADDRESS = 'checkout_remove_billing_address';

    // Routes for the Cart
    public const CART_INDEX = 'cart_index';
    public const CART_ADD = 'cart_add';
    public const CART_ADD_INDEX = 'cart_add_index';
    public const CART_REMOVE = 'cart_remove';
    public const CART_REMOVE_ALL = 'cart_remove_all';

    // Routes for the Orders
    public const ORDER = 'order';
    public const ORDER_CANCEL = 'order_cancel';

    // Token for the uri
    public const ORDER_TOKEN = '?token=';
    public const ORDER_SESSION_ID = '&session_id={CHECKOUT_SESSION_ID}';
    public const ORDER_SESSION_ID_MARK = '?session_id={CHECKOUT_SESSION_ID}';

    // Errors messages in the Controllers
    public const BASIC_ERROR = 'danger';
    public const BASIC_SUCCESS = 'success';

    /**
     * Checks if the current locale exists
     *
     * @param array $locales
     * @param string $locale
     * @return bool
     */
    public static function checkAuthorizedLocales(array $locales, string $locale): bool
    {
        return in_array($locale, $locales);
    }
}