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

Make sure `{{ stripe_script() }}` is rendered on every page. It includes
Stripe's JS. Stripe checks for fraudelant behaviour on each page its script is
included, so the more the better.

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

## Initializing the Stripe JS

```twig
<script>
  const stripe = OHMEDIA_STRIPE('{{ form.stripe.vars.id }}');

  // provide billing information
  const cardData = {
    'name': ...,
    'address_line1': ...,
    'address_line2': ...,
    'address_city': ...,
    'address_state': ...,
    'address_zip': ...,
    'address_country': ...,
  };

  stripe.createToken(cardData).then(function(errorMessage) {
    if (errorMessage) {
      // do something with errorMessage
    } else {
      // continue with form submission
    }
  });
</script>
```

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
