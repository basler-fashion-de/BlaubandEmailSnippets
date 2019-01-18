<?php

use Shopware\Components\CSRFWhitelistAware;
use Shopware\Models\Shop\Shop;
use Shopware\Components\Model\ModelManager;
use Shopware\Models\Snippet\Snippet;

class Shopware_Controllers_Backend_BlaubandEmailSnippets extends \Enlight_Controller_Action implements CSRFWhitelistAware
{
    /** @var Shopware_Components_Snippet_Manager */
    private $snippetsManager;

    /** @var ModelManager */
    private $modelManager;

    /** @var array */
    private $shops;

    public function getWhitelistedCSRFActions()
    {
        return [
            'index',
            'save',
        ];
    }

    public function preDispatch()
    {
        $this->snippetsManager = $this->container->get('snippets');
        $this->modelManager = $this->container->get('models');

        $repository = $this->modelManager->getRepository(Shop::class);
        $this->shops = $repository->findAll();

        $pluginPath = $this->container->getParameter('shopware.plugin_directories')['ShopwarePlugins'];

        $this->view->addTemplateDir($pluginPath . "BlaubandEmail/Resources/views");
        $this->view->addTemplateDir(__DIR__ . "/../../Resources/views");
    }

    public function indexAction()
    {
        $snippetName = $this->request->getParam('snippetName');

        $snippets = [];

        /** @var Shop $shop */
        foreach ($this->shops as $shop) {
            $this->snippetsManager->setShop($shop);
            $value = $this->snippetsManager
                ->getNamespace(\BlaubandEmailSnippets\Subscribers\Backend::$customSnippetNamespace)
                ->get($snippetName);

            $snippets[$shop->getId()]['shopName'] = $shop->getName();
            $snippets[$shop->getId()]['shopLocale'] = $shop->getLocale()->getLocale();
            $snippets[$shop->getId()]['value'] = $value;
        }

        $this->view->assign('snippets', $snippets);
        $this->view->assign('snippetName', $snippetName);
        $this->view->assign('saveSuccess', $this->request->getParam('saveSuccess'));
    }

    public function saveAction()
    {
        $params = $this->request->getParams();
        $snippetName = $params['snippetName'];

        /** @var Shop $shop */
        foreach ($this->shops as $shop) {
            if (!isset($params['snippet-' . $shop->getId()])) {
                continue;
            }

            $value = $params['snippet-' . $shop->getId()];
            $snippetNamespace = \BlaubandEmailSnippets\Subscribers\Backend::$customSnippetNamespace;

            $snippetRepository = $this->modelManager->getRepository(Snippet::class);
            $snippet = $snippetRepository->findOneBy(
                [
                    'shopId' => $shop->getId(),
                    'localeId' => $shop->getLocale()->getId(),
                    'namespace' => $snippetNamespace,
                    'name' => $snippetName
                ]
            );

            if(empty($snippet)){
                $snippet = new Snippet();
                $snippet->setShopId($shop->getId());
                $snippet->setLocaleId($shop->getLocale()->getId());
                $snippet->setNamespace($snippetNamespace);
                $snippet->setName($snippetName);

                $this->modelManager->persist($snippet);
            }

            $snippet->setValue($value);
            $snippet->setUpdated();
        }

        $this->modelManager->flush();

        $this->Front()->Plugins()->ViewRenderer()->setNoRender();
        $this->Response()->setBody(json_encode(['success' => true]));
        $this->Response()->setHeader('Content-type', 'application/json', true);
    }
}
