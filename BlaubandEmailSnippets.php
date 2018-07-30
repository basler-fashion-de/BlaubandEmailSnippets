<?php

namespace BlaubandEmailSnippets;

use Shopware\Components\Plugin;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Shopware-Plugin BlaubandEmailSnippets.
 */
class BlaubandEmailSnippets extends Plugin
{

    /**
    * @param ContainerBuilder $container
    */
    public function build(ContainerBuilder $container)
    {
        $container->setParameter('blauband_email_snippets.plugin_dir', $this->getPath());
        parent::build($container);
    }
}
