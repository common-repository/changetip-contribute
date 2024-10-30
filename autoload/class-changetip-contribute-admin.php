<?php

namespace ChangeTip_Contribute;

require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-hookable.php';

class ChangeTip_Contribute_Admin extends Hookable {
    public function __construct() {
        parent::__construct();
        $this->define_hooks();
	}

    public function define_hooks() {
        if( is_admin() ) {
            $this->add_action( 'admin_enqueue_scripts' );
            $this->add_action( 'admin_menu' );
            $this->add_action( 'admin_init' );
            $this->add_action( 'add_meta_boxes' );
            $this->add_action( 'save_post' );
            $this->attach_hooks();
        }
    }

    public function admin_init() {
        register_setting( 'changepay-connect-options', 'changepay_site_id' );
        register_setting( 'changepay-connect-options', 'changepay_remove_google_ads' );
        register_setting( 'changepay-connect-options', 'changepay_debug' );
        register_setting( 'changepay-messages-options', 'changepay_message_login_required' );
        register_setting( 'changepay-messages-options', 'changepay_type_login_required' );
        register_setting( 'changepay-messages-options', 'changepay_message_payment_successful' );
        register_setting( 'changepay-messages-options', 'changepay_type_payment_successful' );
        register_setting( 'changepay-messages-options', 'changepay_message_payment_already_made' );
        register_setting( 'changepay-messages-options', 'changepay_type_payment_already_made' );
        register_setting( 'changepay-messages-options', 'changepay_message_payment_not_required' );
        register_setting( 'changepay-messages-options', 'changepay_type_payment_not_required' );
        register_setting( 'changepay-messages-options', 'changepay_message_payment_would_exceed_user_cap' );
        register_setting( 'changepay-messages-options', 'changepay_type_payment_would_exceed_user_cap' );
    }

    public function admin_enqueue_scripts() {
        wp_register_script( 'changepay_admin_js', $this->plugin_dir_url . 'public/scripts/admin.js', array( 'jquery-core' ), '1.0.0' );
        wp_enqueue_script( 'changepay_admin_js' );

        wp_register_style( 'changepay_admin_css', $this->plugin_dir_url . 'public/css/editor.css', FALSE, '1.0.0' );
        wp_enqueue_style( 'changepay_admin_css' );
    }

    public function admin_menu() {
        add_menu_page(
            'ChangeTip Contribute',
            'Contribute',
            'manage_options',
            $this->plugin_name,
            NULL,
            $this->plugin_dir_url . 'public/images/changetip-contribute-icon.png',
            99
        );

        add_submenu_page(
            $this->plugin_name,
            'Connect',
            'Connect',
            'manage_options',
            $this->plugin_name,
            array( $this, 'menu_connect' )
        );

        add_submenu_page(
            $this->plugin_name,
            'Messages',
            'Messages',
            'manage_options',
            $this->plugin_name . '-messages',
            array( $this, 'menu_messages' )
        );
    }

    public function menu_connect() {
        include( $this->plugin_dir . 'admin/templates/menu-connect.php' );
    }

    public function menu_messages() {
        include( $this->plugin_dir . 'admin/templates/menu-messages.php' );
    }

    public function add_meta_boxes( $post_type ) {
        add_meta_box(
            $this->plugin_name,
            'Contribute',
            array( $this, 'metabox_post' ),
            NULL,
            'side',
            'default'
        );
    }

    public function metabox_post() {
        include( $this->plugin_dir . 'admin/templates/metabox-post.php' );
    }

    public function save_post( $post_id ) {
        if ( !isset( $_POST['changepay_metabox_nonce'] ) )
        	return $post_id;

        $nonce = $_POST['changepay_metabox_nonce'];

        if ( !wp_verify_nonce( $nonce, 'changepay_update_post' ) )
            return $post_id;

        if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
			return $post_id;

        //TODO: check edit permissions

        $changepay_active = isset( $_POST['changepay_active'] ) ? 1 : -1;
        update_post_meta( $post_id, 'changepay_active', $changepay_active );
    }
}
