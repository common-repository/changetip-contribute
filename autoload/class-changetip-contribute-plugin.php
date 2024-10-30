<?php

namespace ChangeTip_Contribute;

require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-hookable.php';
require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-changetip-contribute-admin.php';
require_once CHANGETIP_CONTRIBUTE_DIR . '/autoload/class-changetip-contribute-public.php';

class ChangeTip_Contribute_Plugin extends Hookable {
    public function __construct() {
        parent::__construct();
        ChangeTip_Contribute_Admin::load();
        ChangeTip_Contribute_Public::load();
    }
}
