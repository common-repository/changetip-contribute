<?php
    $messages = array(
        array( 'login_required', 'Login required', '<p>You\'re not logged in.</p>' ),
        array( 'payment_successful', 'Payment successful', '<p>Paid! Thanks for the support!</p>' ),
        array( 'payment_already_made', 'Payment already made', '<p>Payment was already made.</p>' ),
        array( 'payment_not_required', 'Payment not requried', '<p>Payment is not required</p>' ),
        array( 'payment_would_exceed_user_cap', 'Payment would exceed user cap', '<p>Page views are now free for the rest of the day!</p>' )
    );
?>

<div class="wrap">
    <h2>ChangeTip Contribute &mdash; Messages</h2>
    <p>Leave the following sections blank to use the default message displayed above each section.</p>
    <form method="POST" action="options.php">
        <?php settings_fields( 'changepay-messages-options' ); ?>
        <?php do_settings_sections( 'changepay-messages-options' ) ?>
        <table class="form-table">
            <?php foreach( $messages as $message ) :
                $message_key = 'changepay_message_' . $message[0];
                $type_key = 'changepay_type_' . $message[0];
                $label = $message[1];
                $default_message = $message[2];
            ?>
                <tr valign="top">
                    <th scope="row" colspan="2">
                        <hr/>
                    </th>
                </tr>
                <tr valign="top">
                    <td colspan="2">

                        <h2 class="title" style="margin-top:-1em;"><?php echo $label; ?></h2>
                        <select id="<?php echo $type_key ?>" name="<?php echo $type_key ?>">
                            <option value="modal" <?php selected( 'modal', get_option( $type_key ) ) ?>>Modal Popup</option>
                            <option value="alert" <?php selected( 'alert', get_option( $type_key ) ) ?>>Alert</option>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2">
                        <strong>Default message:</strong><br/>
                        <?php echo $default_message ?>
                    </td>
                </tr>
                <tr valign="top">
                    <td colspan="2">
                        <?php wp_editor( get_option( $message_key ) , $message_key, array(
                            'media_buttons' => FALSE,
                            'textarea_name' => $message_key,
                            'textarea_rows' => 5
                        ) ); ?>
                    </th>
                </tr>
            <?php endforeach; ?>
        </table>
        <?php submit_button(); ?>
    </form>
</div>
