<?php

namespace BlaubandEmailSnippets\Subscribers;

use Enlight\Event\SubscriberInterface;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Shop\Shop;

class Backend implements SubscriberInterface
{
    public static $customSnippetNamespace = 'blauband/mail_custom_snippets';

    /**  @var string */
    private $pluginDirectory;

    /** @var ModelManager $modelManager */
    private $modelManager;

    /** @var \Enlight_Components_Snippet_Manager $snippets */
    private $snippets;

    /** @var \Zend_Locale */
    private $locale;

    /**
     * @param $pluginDirectory
     */
    public function __construct(
        $pluginDirectory,
        ModelManager $modelManager,
        \Enlight_Components_Snippet_Manager $snippets,
        $container)
    {
        $this->pluginDirectory = $pluginDirectory;
        $this->modelManager = $modelManager;
        $this->snippets = $snippets;
        $this->locale = $container->get('locale');

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
            $data = $this->snippets->getNamespace(self::$customSnippetNamespace)->toArray();

            foreach ($data as $name => $value) {
                $customSnippets[$name][$shop->getId()]['value'] = $value;
                $customSnippets[$name][$shop->getId()]['shopName'] = $shop->getName();
                $customSnippets[$name][$shop->getId()]['locale'] = $shop->getLocale()->getLocale();
                $customSnippets[$name][$shop->getId()]['lang'] = strtolower(explode('_', $shop->getLocale()->getLocale())[1]);

            }

            if (
                $shop->getLocale()->getLocale() == $this->locale->toString() &&
                empty($view->getAssign('authShopId'))
            ) {
                $view->assign('authShopId', $shop->getId());
            }
        }

        $view->assign('customSnippets', $customSnippets);

        $view->addTemplateDir($this->pluginDirectory . '/Resources/views');
    }


}