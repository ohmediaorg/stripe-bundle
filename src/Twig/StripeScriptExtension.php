<?php

namespace OHMedia\StripeBundle\Twig;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Twig\Environment;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class StripeScriptExtension extends AbstractExtension
{
    private bool $rendered = false;

    public function __construct(
        #[Autowire('%oh_media_stripe.publishable_key%')]
        private string $publishableKey,
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('stripe_script', [$this, 'script'], [
                'is_safe' => ['html'],
                'needs_environment' => 'true',
            ]),
        ];
    }

    public function script(Environment $twig)
    {
        if ($this->rendered) {
            return '';
        }

        $this->rendered = true;

        $options = [
            'hidePostalCode' => true,
            // https://docs.stripe.com/js/appendix/style
            'style' => [
                'base' => [
                    'color' => '#212529',
                    'fontFamily' => '"Helvetica Neue", Helvetica, sans-serif',
                    'fontSmoothing' => 'antialiased',
                    'fontSize' => '16px',
                    '::placeholder' => [
                        'color' => '#aab7c4',
                    ],
                ],
                'invalid' => [
                    'color' => '#dc3545',
                    'iconColor' => '#dc3545',
                ],
            ],
            // https://docs.stripe.com/js/elements_object/create_element?type=card#elements_create-options-classes
            'classes' => [
                'base' => 'form-control',
                'complete' => '',
                'empty' => '',
                'focus' => '',
                'invalid' => 'is-invalid',
                'webkitAutofill' => '',
            ],
        ];

        return $twig->render('@OHMediaStripe/stripe_script.html.twig', [
            'publishable_key' => $this->publishableKey,
            'options' => json_encode($options),
        ]);
    }
}
