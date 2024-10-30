<?php

namespace ChangeTip_Contribute;

require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-hookable.php';

class Ajax_The_Content extends Hookable {
    public function __construct() {
        parent::__construct();
        $this->define_hooks();
    }

    public function define_hooks() {
        $this->add_action( 'wp_ajax_nopriv_the_content_unauthenticated', 'the_content_unauthenticated' );
        $this->add_action( 'wp_ajax_the_content_unauthenticated', 'the_content_unauthenticated' );

        $this->add_action( 'wp_ajax_nopriv_the_content_paid', 'the_content_paid' );
        $this->add_action( 'wp_ajax_the_content_paid', 'the_content_paid' );

        $this->add_action( 'wp_ajax_nopriv_the_content_unpaid', 'the_content_unpaid' );
        $this->add_action( 'wp_ajax_the_content_unpaid', 'the_content_unpaid' );
        $this->attach_hooks();
    }

    protected function get_the_content( $post_id ) {
        $content_post = get_post( $post_id );
        $content = $content_post->post_content;
        $content = apply_filters( 'the_content', $content );
        $content = str_replace( ']]>', ']]&gt;', $content );
        return $content;
    }

    protected function respond_with( $content ) {
        $response = array(
            'content' => $content
        );
        wp_send_json( $response );
        die();
    }

    protected function the_content( $filter ) {
        check_ajax_referer( 'changepay_ajax_the_content', 'nonce' );
        $post_id = $_POST['post_id'];
        $content = $this->get_the_content( $post_id );
        $content = apply_filters( $filter, $content );
        $this->respond_with( $content );
    }

    public function the_content_unauthenticated() {
        $this->the_content( 'the_content_unauthenticated' );
    }

    public function the_content_unpaid() {
        $this->the_content( 'the_content_unpaid' );
    }

    public function the_content_paid() {
        $this->the_content( 'the_content_paid' );
    }
}
