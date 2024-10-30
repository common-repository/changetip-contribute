<?php $site_id = get_option('changepay_site_id'); ?>


<div class="wrap">
    <h2>ChangeTip Contribute &mdash; Connect Your Account </h2>
    <form class="changepay-form" method="POST" action="options.php">
        <?php settings_fields( 'changepay-connect-options' ); ?>
        <?php do_settings_sections( 'changepay-connect-options' ) ?>

        <table class="form-table">
            <?php if ( $site_id ) : ?>
                <tr valign="top">
                    <th scope="row">Site ID</th>
                    <td>
                        <input type="text" name="changepay_site_id" value="<?php echo esc_attr( $site_id ); ?>" class="regular-text" />
                        <p class="description"><a data-changepay-modal-link="create" href="#">Create a new site</a> or <a data-changepay-modal-link="connect" href="#">connect or edit an existing site</a>.</p>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">Google AdSense</th>
                    <td>
                        <label for="changepay_remove_google_ads">
                            <input type="checkbox" name="changepay_remove_google_ads" value="1" class="code" <?php checked( 1, get_option( 'changepay_remove_google_ads' ) ) ?> />
                            Remove Google Ads for paying users.
                        </label>
                        <p class="description">
                            More ad partners coming soon. Let us know which ad partner you’d like supported at <a href="mailto:support@changetip.com">support@changetip.com</a>.
                        </p>
                    </td>
                </tr>
                <!--
                <tr valign="top">
                    <th scope="row">Debug</th>
                    <td>
                        <label for="changepay_debug">
                            <input type="checkbox" name="changepay_debug" value="1" class="code" <?php checked( 1, get_option( 'changepay_debug' ) ) ?> />
                            Run in debug mode.
                        </label>
                    </td>
                </tr>
                -->
            <?php else : ?>
                <tr valign="top">
                    <th scope="row">
                        <a class="button button-primary" data-changepay-modal-link="create" href="#">Create a new site</a> <a class="button" data-changepay-modal-link="connect" href="#">Connect an existing site</a>
                        <input type="hidden" name="changepay_site_id" value="<?php echo esc_attr( $site_id ); ?>" class="regular-text" />
                    </th>
                </tr>
            <?php endif; ?>
        </table>

        <?php if ( $site_id ) : ?>
            <?php submit_button(); ?>
        <?php else : ?>
            <div style="display:none;">
                <?php submit_button(); ?>
            </div>
        <?php endif; ?>
    </form>
</div>

<div id="changepay-modal-curtain"></div>
<!-- Connect Existing Site -->
<div class="changepay-modal" data-changepay-modal="connect" data-src="https://www.changetip.com/contribute/publishers/sites/">
    <div class="changepay-modal-align">
    </div>
</div>

<!-- Create a New Site -->
<div class="changepay-modal" data-changepay-modal="create" data-src="https://www.changetip.com/contribute/publishers/sites/add/">
    <div class="changepay-modal-align">
    </div>
</div>
