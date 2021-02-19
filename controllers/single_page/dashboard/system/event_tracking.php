<?php

namespace Concrete\Package\EventTracking\Controller\SinglePage\Dashboard\System;

use Concrete\Core\Page\Controller\DashboardPageController;
use Concrete\Core\Routing\Redirect;

final class EventTracking extends DashboardPageController
{
    public function view()
    {
        return Redirect::to('/dashboard/system/event_tracking/settings');
    }
}
