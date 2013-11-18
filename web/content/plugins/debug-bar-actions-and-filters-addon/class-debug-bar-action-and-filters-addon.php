<?php
	/**
	 * This file contains the classes those create two new tabs in the debug panel provided by Debug Bar plugin.
	 * It extends the funtionality provided by the parent plugin "Debug Bar".
	 */
	class Debug_Bar_Actions_Addon_Panel extends Debug_Bar_Panel {

		private $tab_name;
		private $tab;
		private $callback;

		public function init() {
			$this->title( $this->tab );
		}

		public function set_tab( $name, $callback ) {
			$this->tab_name = strtolower( preg_replace( "#[^a-z0-9]#msiU", "", $name ) );
			$this->tab      = $name;
			$this->callback = $callback;
			$this->title( $this->tab );
		}

		public function prerender() {
			$this->set_visible( true );
		}

		public function render() {
			echo call_user_func( $this->callback );
		}
	}

	class Debug_Bar_Filters_Addon_Panel extends Debug_Bar_Panel {

		private $tab_name;
		private $tab;
		private $callback;

		public function init() {
			$this->title( $this->tab );
		}

		public function set_tab( $name, $callback ) {
			$this->tab_name = strtolower( preg_replace( "#[^a-z0-9]#msiU", "", $name ) );
			$this->tab      = $name;
			$this->callback = $callback;
			$this->title( $this->tab );
		}

		public function prerender() {
			$this->set_visible( true );
		}

		public function render() {
			echo call_user_func( $this->callback );
		}
	}