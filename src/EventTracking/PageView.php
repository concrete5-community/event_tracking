<?php

namespace A3020\EventTracking;

use Concrete\Core\Asset\AssetList;
use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Http\ResponseAssetGroup;
use Exception;
use Psr\Log\LoggerInterface;

class PageView
{
    /**
     * @var \Concrete\Core\Config\Repository\Repository
     */
    private $config;

    /**
     * @var \Psr\Log\LoggerInterface
     */
    private $logger;

    public function __construct(Repository $config, LoggerInterface $logger)
    {
        $this->config = $config;
        $this->logger = $logger;
    }

    public function handle($event)
    {
        try {
            $this->registerGoogleAnalytics();
        } catch (Exception $e) {
            $this->logger->debug($e->getMessage());
        }
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
