<?php

namespace OHMedia\StripeBundle\Service;

use Stripe\StripeClient;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

class Stripe
{
    private StripeClient $client;

    public function __construct(
        #[Autowire('%oh_media_stripe.secret_key%')]
        string $secretKey
    ) {
        $this->client = new StripeClient($secretKey);
    }

    public function call(string $api, string $action)
    {
        $args = func_num_args() > 2
            ? array_slice(func_get_args(), 2)
            : [];

        try {
            $object = call_user_func_array(
                [$this->client->{$api}, $action],
                $args
            );
        } catch (\Exception $e) {
            $object = null;
        }

        return $object;
    }
}
