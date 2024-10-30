<?php

namespace ChangeTip_Contribute;

abstract class Hookable {
	protected $actions;
	protected $filters;
	protected $plugin_dir;
	protected $plugin_dir_url;
	protected $plugin_name = "changetip_contribute";
	protected $version = "1.0.0";

    public function __construct() {
		$this->actions = array();
		$this->filters = array();
		$this->plugin_dir = plugin_dir_path( dirname( __FILE__ ) );
		$this->plugin_dir_url = plugin_dir_url( dirname( __FILE__ ) );
	}

    public function add_action( $hook, $callback = NULL, $priority = 10, $accepted_args = 1 ) {
		$this->actions = $this->add( $this->actions, $hook, $this, $callback, $priority, $accepted_args );
	}

    public function add_filter( $hook, $callback = NULL, $priority = 10, $accepted_args = 1 ) {
		$this->filters = $this->add( $this->filters, $hook, $this, $callback, $priority, $accepted_args );
	}

    private function add( $hooks, $hook, $component, $callback, $priority, $accepted_args ) {
		$hooks[] = array(
			'hook'          => $hook,
			'component'     => $component,
			'callback'      => $callback ? $callback : $hook,
			'priority'      => $priority,
			'accepted_args' => $accepted_args
		);

		return $hooks;
	}

    public function attach_hooks() {
		foreach ( $this->filters as $hook ) {
			add_filter( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}

		foreach ( $this->actions as $hook ) {
			add_action( $hook['hook'], array( $hook['component'], $hook['callback'] ), $hook['priority'], $hook['accepted_args'] );
		}
	}

	private static $loaded_classes = array();

	public static function load() {
		$class = get_called_class();
		if( !isset( Hookable::$loaded_classes[ $class ] ) ) {
			$class_instance = new $class;
			Hookable::$loaded_classes[ $class ] = $class_instance;
		}
		return Hookable::$loaded_classes[ $class ];
	}
}
