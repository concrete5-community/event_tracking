<?php

namespace Concrete\Package\EventTracking\Controller\SinglePage\Dashboard\System\EventTracking;

use Concrete\Core\Config\Repository\Repository;
use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class Settings extends DashboardPageController
{
    public function view()
    {
        /** @var Repository $config */
        $config = $this->app->make(Repository::class);

        $this->set('enableGoogleAnalytics', (bool) $config->get('event_tracking::settings.integrations.google_analytics', true));
    }

    public function save()
    {
        if (!$this->token->validate('a3020.event_tracking.settings')) {
            $this->flash('error', $this->token->getErrorMessage());

            return Redirect::to('/dashboard/system/event_tracking/settings');
        }

        /** @var Repository $config */
        $config = $this->app->make(Repository::class);
        $config->save('event_tracking::settings.integrations.google_analytics', (bool) $this->post('enableGoogleAnalytics'));

        $this->flash('success', t('Your settings have been saved.'));

        return Redirect::to('/dashboard/system/event_tracking/settings');
    }
}
