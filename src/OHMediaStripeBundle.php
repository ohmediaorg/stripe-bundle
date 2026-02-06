<?php

namespace OHMedia\StripeBundle;

use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class OHMediaStripeBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('publishable_key')->isRequired()->end()
                ->scalarNode('secret_key')->isRequired()->end()
            ->end()
        ;
    }

    public function loadExtension(
        array $config,
        ContainerConfigurator $containerConfigurator,
        ContainerBuilder $containerBuilder
    ): void {
        $containerConfigurator->import('../config/services.yaml');

        $containerBuilder->setParameter(
            'oh_media_stripe.publishable_key',
            $config['publishable_key'],
        );

        $containerBuilder->setParameter(
            'oh_media_stripe.secret_key',
            $config['secret_key'],
        );

        $this->registerWidget($containerBuilder);
    }

    /**
     * Registers the form widget.
     */
    protected function registerWidget(ContainerBuilder $containerBuilder)
    {
        $resource = '@OHMediaStripe/stripe_widget.html.twig';

        $containerBuilder->setParameter('twig.form.resources', array_merge(
            $containerBuilder->getParameter('twig.form.resources'),
            [$resource]
        ));
    }
}
