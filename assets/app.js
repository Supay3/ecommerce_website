/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';

/**
 * The success block displayed when an item is added to cart successfully
 *
 * @param {HTMLButtonElement} addCartButton
 * @param {string} responseData
 */
const successBlock = function (addCartButton, responseData) {
    console.log(responseData)
    let successBlock = addCartButton.parentElement.parentElement.querySelector('.success-add-cart');
    successBlock.innerHTML = responseData;
    successBlock.removeAttribute('hidden');
    window.setTimeout(function () {
        successBlock.setAttribute('hidden', '');
    }, 1500);
}

/**
 * The error block displayed when an item is not added to cart successfully
 *
 * @param {HTMLButtonElement} addCartButton
 * @param {string} responseData
 */
const errorBlock = function (addCartButton, responseData) {
    let errorBlock = addCartButton.parentElement.parentElement.querySelector('.error-add-cart');
    errorBlock.innerHTML = responseData;
    errorBlock.removeAttribute('hidden');
    window.setTimeout(function () {
        errorBlock.setAttribute('hidden', '');
    }, 1500);
}

/**
 * The function to add an item to the cart
 */
const addToCart = function () {
    let addCartButtons = document.querySelectorAll('.add-cart');
    addCartButtons.forEach(function (addCartButton) {
        if (!addCartButton.classList.contains('disabled')) {
            addCartButton.addEventListener('click', (e) => {
                e.preventDefault();
                e.stopPropagation();
                let data = addCartButton.getAttribute('data-fetch');
                let token = addCartButton.getAttribute('data-token');
                fetch(data, {method: 'POST', body: token}).then((response) => {
                    response.json().then((responseData) => {
                        if (!response.ok && response.status !== 200) {
                            errorBlock(addCartButton, responseData);
                            return;
                        }
                        successBlock(addCartButton, responseData);

                    });
                }).catch(() => {
                    errorBlock(addCartButton, 'Une erreur est survenue, merci de réessayer ou de le signaler');
                });
            });
        }
    });
}

/**
 * The listener function to add a billing address
 */
const addBillingListener = function () {
    let billingForm = document.querySelector('.billing-form');
    billingForm.parentElement.firstChild.removeAttribute('hidden');
    billingForm.parentElement.lastChild.removeAttribute('hidden');
    let addBillingButton = document.querySelector('.add-billing-address');
    addBillingButton.setAttribute('hidden', '');
    let removeBillingButton = document.querySelector('.remove-billing-address');
    if (removeBillingButton.hasAttribute('hidden')) {
        removeBillingButton.removeAttribute('hidden');
    }
    removeBilling();
}

/**
 * The listener function to remove a billing address
 */
const removeBillingListener = function () {
    let billingForm = document.querySelector('.billing-form');
    billingForm.parentElement.firstChild.setAttribute('hidden', '');
    billingForm.parentElement.lastChild.setAttribute('hidden', '');
    billingForm.querySelectorAll('.form-control').forEach((input) => {
        input.value = '';
    });
    let removeBillingButton = document.querySelector('.remove-billing-address');
    let token = removeBillingButton.getAttribute('data-token');
    fetch('http://localhost:8000/fr_FR/shop/checkout/remove_billing', {method: 'POST', body: token}).then((response) => {
        if (response.ok && response.status === 200) {
            let addBillingButton = document.querySelector('.add-billing-address');
            if (addBillingButton.hasAttribute('hidden')) {
                addBillingButton.removeAttribute('hidden');
            }
            removeBillingButton.setAttribute('hidden', '');
            addBilling();
        }
    });
}

/**
 * The billing function to add a billing address
 */
const addBilling = function () {
    let addBillingButton = document.querySelector('.add-billing-address');
    addBillingButton.addEventListener('click', addBillingListener);
}

/**
 * The billing function to remove a billing address
 */
const removeBilling = function () {
    let removeBillingButton = document.querySelector('.remove-billing-address');
    removeBillingButton.addEventListener('click', removeBillingListener);
}

if (document.querySelectorAll('.add-cart')) {
    addToCart();
}

if (document.querySelector('.add-billing-address')) {
    addBilling();
}

if (document.querySelector('.remove-billing-address')) {
    removeBilling();
}

// For the profile :

if (document.querySelectorAll('.nav-btn')) {
    let addressesButton = document.querySelector('#addresses-btn');
    let infosButton = document.querySelector('#infos-btn');
    let preferencesButton = document.querySelector('#preferences-btn');

    let addressesCard = document.querySelector('#addresses');
    let infosCard = document.querySelector('#infos');
    let preferencesCard = document.querySelector('#preferences');

    addressesButton.addEventListener('click', () => {
        addressesButton.classList.add('active');
        addressesCard.removeAttribute('hidden');

        infosButton.classList.remove('active');
        infosCard.setAttribute('hidden', '');

        preferencesButton.classList.remove('active');
        preferencesCard.setAttribute('hidden', '');
    });

    infosButton.addEventListener('click', () => {
        infosButton.classList.add('active');
        infosCard.removeAttribute('hidden');

        addressesButton.classList.remove('active');
        addressesCard.setAttribute('hidden', '');

        preferencesButton.classList.remove('active');
        preferencesCard.setAttribute('hidden', '');
    });

    preferencesButton.addEventListener('click', () => {
        preferencesButton.classList.add('active');
        preferencesCard.removeAttribute('hidden');

        addressesButton.classList.remove('active');
        addressesCard.setAttribute('hidden', '');

        infosButton.classList.remove('active');
        infosCard.setAttribute('hidden', '');
    });
}



















