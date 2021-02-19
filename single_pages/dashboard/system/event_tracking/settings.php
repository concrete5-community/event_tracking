<?php

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Core\Support\Facade\Url;

/** @var \Concrete\Core\Validation\CSRF\Token $token */
/** @var bool $enableGoogleAnalytics */
?>

<div class="ccm-dashboard-content-inner">
    <form method="post" action="<?php echo $this->action('save'); ?>">
        <?php
        echo $token->output('a3020.event_tracking.settings');
        ?>

        <div class="alert alert-info">
            <p>
                <?php
                echo t('If you want to disable the CKEditor Event Tracking plugin, go to the %s page.',
                    '<a href="' . Url::to('/dashboard/system/basics/editor') . '">' . t('Rich Text Editor settings') . '</a>'
                );
                ?>
            </p>
        </div>

        <hr>

        <div class="form-group">
            <label class="control-label launch-tooltip"
                   title="<?php echo t('If enabled, events will be sent to Google Analytics.') ?>"
                   for="enableGoogleAnalytics">
                <?php
                echo $form->checkbox('enableGoogleAnalytics', 1, $enableGoogleAnalytics);
                ?>
                <?php echo t('Enable Google Analytics integration'); ?>
            </label>
        </div>

        <div class="ccm-dashboard-form-actions-wrapper">
            <div class="ccm-dashboard-form-actions">
                <button class="pull-right btn btn-primary" type="submit">
                    <?php echo t('Save') ?>
                </button>
            </div>
        </div>
    </form>
</div>
