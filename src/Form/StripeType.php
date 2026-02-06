<?php

namespace OHMedia\StripeBundle\Form;

use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StripeType extends AbstractType
{
    public function __construct(
        #[Autowire('%oh_media_stripe.publishable_key%')]
        private string $publishableKey
    ) {
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'mapped' => false,
            'hide_postal_code' => true,
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
        ]);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->setAttribute('hide_postal_code', $options['hide_postal_code']);
        $builder->setAttribute('style', $options['style']);
        $builder->setAttribute('classes', $options['classes']);

        $builder
            ->add('token', HiddenType::class)
            ->add('last4', HiddenType::class)
        ;
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['publishable_key'] = $this->publishableKey;
        $view->vars['options'] = json_encode([
            'hidePostalCode' => (bool) $options['hide_postal_code'],
            'style' => $options['style'],
            'classes' => $options['classes'],
        ]);
    }

    public function getParent(): ?string
    {
        return FormType::class;
    }
}
