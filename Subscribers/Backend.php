<?php

namespace BlaubandEmailSnippets\Subscribers;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Shop\Shop;

class Backend implements SubscriberInterface
{
    private $customSnippetNamespace = 'blauband/mail_custom_snippets';

    /**  @var string */
    private $pluginDirectory;

    /** @var ModelManager $modelManager */
    private $modelManager;

    /** @var \Enlight_Components_Snippet_Manager $snippets */
    private $snippets;


    /**
     * @param $pluginDirectory
     */
    public function __construct($pluginDirectory, ModelManager $modelManager, \Enlight_Components_Snippet_Manager $snippets)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->modelManager = $modelManager;
        $this->snippets = $snippets;


    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PreDispatch_Backend_BlaubandEmail' => 'onBackendBlaubandEmail'
        ];
    }

    public function onBackendBlaubandEmail(\Enlight_Event_EventArgs $args)
    {
        /** @var \Shopware_Controllers_Backend_BlaubandEmail $subject */
        $subject = $args->getSubject();

        /** @var \Enlight_View_Default $view */
        $view = $subject->View();

        $repository = $this->modelManager->getRepository(Shop::class);
        $shops = $repository->findAll();
        $customSnippets = [];
        foreach ($shops as $shop) {
            $this->snippets->setShop($shop);
            $name = $shop->getName() . ' / ' . $shop->getLocale()->getLocale();
            $data = $this->snippets->getNamespace($this->customSnippetNamespace)->toArray();
            $customSnippets[$shop->getId()]['name'] = $name;
            $customSnippets[$shop->getId()]['data'] = $data;
        }

        $view->assign('customSnippets', $customSnippets);

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
//        $view->extendsTemplate('backend/blauband_email/send.tpl');
    }


}