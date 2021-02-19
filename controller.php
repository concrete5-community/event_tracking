<?php

namespace Concrete\Package\EventTracking;

use A3020\EventTracking\Installer;
use A3020\EventTracking\Provider;
use Concrete\Core\Package\Package;

final class Controller extends Package
{
    protected $pkgHandle = 'event_tracking';
    protected $appVersionRequired = '8.2.1';
    protected $pkgVersion = '1.1.0';
    protected $pkgAutoloaderRegistries = [
        'src/EventTracking' => '\A3020\EventTracking',
    ];

    public function getPackageName()
    {
        return t('Event Tracking');
    }

    public function getPackageDescription()
    {
        return t('Easily incorporate Event Tracking in CKEditor.');
    }

    public function on_start()
    {
         /** @var Provider $provider */
        $provider = $this->app->make(Provider::class);
        $provider->register();
    }

    public function install()
    {
        $pkg = parent::install();

        /** @var Installer $installer */
        $installer = $this->app->make(Installer::class);
        $installer->install($pkg);
    }
}
