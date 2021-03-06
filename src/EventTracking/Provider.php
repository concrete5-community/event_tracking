<?php

namespace A3020\EventTracking;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Editor\Plugin;
use Exception;
use Psr\Log\LoggerInterface;

class Provider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    protected $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function register()
    {
        try {
            $this->listeners();
            $this->registerEditorPlugin();
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
        }
    }

    private function listeners()
    {
        // This is to prevent issues with C5's Composer.
        $this->app['director']->addListener('on_page_view', function($event) {
            /** @var PageView $listener */
            $listener = $this->app->make(PageView::class);
            $listener->handle($event);
        });
    }

    /**
     * Registers an 'Event Tracking' plugin in CKEditor.
     *
     * @throws \Exception
     */
    private function registerEditorPlugin()
    {
        $al = AssetList::getInstance();

        $al->register(
            'javascript',
            'editor/ckeditor4/event_tracking',
            'js/plugins/event_tracking/plugin.js',
            [],
            'event_tracking'
        );

        $plugin = $this->app->make(Plugin::class);
        $plugin->setKey('event_tracking');
        $plugin->setName('Event Tracking');
        $plugin->requireAsset('javascript', 'editor/ckeditor4/event_tracking');

        /** @var \Concrete\Core\Editor\PluginManager $pluginManager */
        $pluginManager = $this->app->make('editor')
            ->getPluginManager();

        $pluginManager->register($plugin);
    }
}
