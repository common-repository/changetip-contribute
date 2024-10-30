<?php

namespace ChangeTip_Contribute;

require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-hookable.php';
require_once CHANGETIP_CONTRIBUTE_DIR . '/public/ajax/the_content.php';

class ChangeTip_Contribute_Public extends Hookable {
    public function __construct() {
        parent::__construct();
        $this->define_hooks();
        $this->attach_hooks();

        $ajax_the_content = new Ajax_The_Content();
        $ajax_the_content = apply_filters( 'changepay_ajax_the_content', $ajax_the_content );
        $ajax_the_content->load();
	}


    public function define_hooks() {
        $this->add_filter( 'the_content' );
        $this->add_action( 'wp_enqueue_scripts' );
        if( $this->are_google_ads_managed() ) {
            $this->add_action( 'wp_head', 'buffer_start' );
            $this->add_action( 'wp_footer', 'buffer_end' );
        }
    }

    public function insert_message_fields() {
        $message_ids = array(
            'login_required',
            'payment_successful',
            'payment_already_made',
            'payment_not_required',
            'payment_would_exceed_user_cap',
            'user_is_logged_in',
            'user_is_not_logged_in'
        );
        $html = "<div id='changepay-messages' style='display:none !important;'>";
        foreach( $message_ids as $message_id ) {
            $message = get_option( 'changepay_message_' . $message_id );
            $type = get_option( 'changepay_type_' . $message_id );

            if( $message ) {
                if( !$type ) $type = 'modal';
                $html .= "<div class='changepay-message' data-key='$message_id' data-type='$type'>";
                $html .= $message;
                $html .= "</div>";
            }
        }
        $html .= "</div>";
        return $html;
    }

    public function is_paywalled_content() {
        $is_paywalled = TRUE;
        $site_id = get_option( 'changepay_site_id' );
        $post_id = get_the_ID();

        if( !$site_id ) {
            //don't paywall if siteid not specified
            $is_paywalled = FALSE;
        } elseif( is_preview() ) {
            //don't paywall preview pages
            $is_paywalled = FALSE;
        } elseif( !is_single() && !is_page() ) {
            //don't paywall archives
            $is_paywalled = FALSE;
        } elseif( intval( get_post_meta( $post_id, 'changepay_active', TRUE ) ) === -1 ) {
            //don't paywall posts specified as NOT hidden behind paywall
            $is_paywalled = FALSE;
        }

        $is_paywalled = apply_filters( 'changepay_is_paywalled', $is_paywalled, $post_id, $site_id );
        return $is_paywalled;
    }

    public function are_google_ads_managed() {
        return get_option( 'changepay_remove_google_ads' ) ? 'true' : 'false';
    }

    public function the_content( $content ) {
        $is_paywalled = $this->is_paywalled_content();
        $content .= $this->insert_message_fields();
        if( !$is_paywalled ) return $content;

        $site_id = get_option( 'changepay_site_id' );
        $ajaxurl = admin_url('admin-ajax.php');
        $ajax_nonce = wp_create_nonce( "changepay_ajax_the_content" );
        $post_id = get_the_ID();
        $show_google_ads = $this->are_google_ads_managed();
        $debug = get_option( 'changepay_debug' ) ? 'true' : 'false';

        $html  = "<div id='changepay-content'
            data-site-id='$site_id'
            data-debug='$debug'
            data-googleads='$show_google_ads'
            data-post-id='$post_id'
            data-nonce='$ajax_nonce'
            data-ajaxurl='$ajaxurl'>";
        $html .= $content;
        $html .= "</div>";
        return $html;
    }

    public function wp_enqueue_scripts() {
        //wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/plugin-name-admin.css', array(), $this->version, 'all' );
        wp_enqueue_script( $this->plugin_name . '-sdk', 'https://www.changetip.com/public/contribute/contribute-sdk.js', array( 'jquery' ), false );
        wp_enqueue_script( $this->plugin_name . '-paywall' , $this->plugin_dir_url . 'public/scripts/paywall.js', array( 'jquery' ), $this->version, false );
    }

    public function buffer_callback( $buffer ) {
        $buffer = preg_replace( '/<script async src="(.*adsbygoogle.js)"><\/script>/i', '<meta name="adsbygoogle" description="$1" />', $buffer );
        return $buffer;
    }

    public function buffer_start() {
        ob_start( array( $this, 'buffer_callback' ) );
    }

    public function buffer_end() {
        ob_end_flush();
    }
}
