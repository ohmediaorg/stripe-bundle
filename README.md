# Overview

Offers a Stripe Card form field and access to the API.

## Installation

Install Stripe:

```bash
composer require stripe/stripe-php
```

Enable the bundle in `config/bundles.php`:

```php
return [
    // ...
    OHMedia\StripeBundle\OHMediaStripeBundle::class => ['all' => true],
];
```

Make sure Stripe JS `<script src="https://js.stripe.com/clover/stripe.js"></script>`
is on every page. Stripe checks for fraudelant behaviour on each page this
script is included, so the more the better.

## Configuration

Create the following in `config/packages/oh_media_stripe.yaml`:

```yaml
oh_media_stripe:
    publishable_key: '%env(resolve:STRIPE_PUBLISHABLE_KEY)%'
    secret_key: '%env(resolve:STRIPE_SECRET_KEY)%'
```

Then add the values to your `.env` or `.env.local` files as needed:

```
STRIPE_PUBLISHABLE_KEY=''
STRIPE_SECRET_KEY=''
```

## Retrieving a Token

Add the field to a form:

```php
use OHMedia\StripeBundle\Form\Type\StripeType;

$builder->add('stripe', StripeType::class);
```

The Stripe JS token will be available
via `$form->get('stripe')->get('token')->getData()`.

You can also get the last 4 digits of the card
using `$form->get('stripe')->get('last4')->getData()`.

## Passing Billing Information

When the built-in Stripe code creates the token, it will automatically look for
the following selectors within the form to grab billing information:

```js
data-stripe-name
data-stripe-address-line-1
data-stripe-address-line-2
data-stripe-city
data-stripe-state
data-stripe-country
data-stripe-zip
```

There can be multiple of `data-stripe-name` which will be concatenated together
with a `SPACE`, but the rest will only look for one input.

## Accessing the API

Inject the Stripe service into your controller:

```php
use OHMedia\StripeBundle\Service\Stripe;

public function action(Stripe $stripe)
{
    $customer = $stripe->call('customers', 'create', [
        'email' => 'customer@site.com'
    ]);
}
```
