<?php

namespace A3020\EventTracking;

use Concrete\Core\Application\ApplicationAwareInterface;
use Concrete\Core\Application\ApplicationAwareTrait;
use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Editor\Plugin;
use Concrete\Core\Http\ResponseAssetGroup;
use Concrete\Core\Logging\Logger;
use Exception;

class Provider implements ApplicationAwareInterface
{
    use ApplicationAwareTrait;

    /**
     * @var Repository
     */
    protected $config;

    /**
     * @var Logger
     */
    private $logger;

    public function __construct(Repository $config, Logger $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function register()
    {
        try {
            $this->registerEditorPlugin();
            $this->registerGoogleAnalytics();
        } catch (Exception $e) {
            $this->logger->addDebug($e->getMessage());
        }
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

    /**
     * Pushes events to e.g. Google Analytics.
     *
     * @throws \Exception
     */
    private function registerGoogleAnalytics()
    {
        // Only load the JS if this integration is enabled.
        if (!(bool) $this->config->get('event_tracking::settings.integrations.google_analytics', true)) {
            return;
        }

        $al = AssetList::getInstance();

        $al->register(
            'javascript',
            'event_tracking/google_analytics',
            'js/google-analytics.js',
            [],
            'event_tracking'
        );

        $assetGroup = ResponseAssetGroup::get();
        $assetGroup->requireAsset('javascript', 'event_tracking/google_analytics');
    }
}
