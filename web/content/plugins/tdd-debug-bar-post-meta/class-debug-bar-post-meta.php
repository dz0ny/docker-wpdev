<?php
class TDD_Debug_Bar_Post_Meta extends Debug_Bar_Panel {

	function init(){
		$this->title( __( 'Post Meta', 'debug-bar' ) );
		add_action( 'wp_print_styles', array( $this, 'print_styles' ) );
		add_action( 'admin_print_styles', array( $this, 'print_styles' ) );
	}

	public function print_styles() {
		$suffix = defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ? '' : '.min';
		wp_enqueue_style( 'tdd-debug-bar-post-meta', plugins_url( "css/debug-bar-post-meta$suffix.css", __FILE__ ), array(), '20120917' );
	}

	public function prerender() {
		$this->set_visible( true );
	}

	public function render() {
		global $post;

		// If required info isn't present, exit early.
		if ( ! isset( $post ) || empty( $post ) || ! $meta = get_post_custom( $post->ID )	) {
			echo '<p>Nothing to display</p>';
			return;
		}
		?>
			<h2><span>Meta for Post ID:</span><?php echo $post->ID; ?></h2>
			<h2><span>Meta Keys:</span><?php echo count( $meta ); ?></h2>
			<h2><span>Approximate Disk Size:</span><?php echo $this->string_disk_size( serialize( $meta ) ); ?></h2>
			<table class="debug-bar-post-meta">
				<tr>
					<th>Key</th>
					<th>Value</th>
				</tr>
				<?php foreach ( $meta as $key => $value ): ?>
					<tr>
						<td>
							<strong><?php echo $key ?></strong><br>
							<small><?php $keycount = count( $key );
								echo $keycount == 1 ? $keycount . ' value' : $keycount . ' values'; ?>
							</small>
						</td>
						<td>
							<?php var_dump($value); ?>
						</td>
					</tr>
				<?php endforeach; ?>
			</table>
		<?php
	}

	public function string_disk_size( $string ) {
		$size = mb_strlen( $string, DB_CHARSET );
		if($size >= 1024)
			$size = round($size / 1024, 2).' KB';
		else
			$size = $size.' bytes';
		echo $size;
	}

}
