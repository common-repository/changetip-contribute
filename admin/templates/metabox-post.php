<?php

$post_id = get_the_ID();
$is_active = intval( get_post_meta( $post_id, 'changepay_active', TRUE ) );

if( $is_active !== -1 ) $is_active = 1; //new post

wp_nonce_field( 'changepay_update_post', 'changepay_metabox_nonce' );

?>

<label>
    <input value="1" type="checkbox" name="changepay_active" id="changepay_active" <?php checked( 1, $is_active ) ?> />
    opt-in users must pay for this page
</label>
