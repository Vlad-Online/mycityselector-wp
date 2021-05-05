<?php


namespace Mcs\WpModels;


use Mcs\Data;

class McsWidget extends \WP_Widget {
	protected $modes = [
		0 => 'Cities only',
		1 => 'Provinces / States and Cities',
		2 => 'Countries,  Provinces / States and Cities',
		3 => 'Countries and Cities'
	];


	public function __construct() {
		$widget_ops = array(
			'classname'   => 'mcs-widget',
			'description' => 'MyCitySelector - location selector widget',
		);
		parent::__construct( 'mcs_widget', 'MyCitySelector Widget', $widget_ops );
	}

	public function widget( $args, $instance ) {
		//xdebug_break();
		wp_add_inline_script( 'mcs-widget-script', '
		window.mcs={};
		window.mcs.options={};
		window.mcs.options.title=\'' . $instance['title'] . '\';
		window.mcs.options.mode=' . $instance['mode'] . ';
		window.mcs.data=JSON.parse('.json_encode(Data::getInstance()->getWidgetDataJson()).');
		', 'before' );
		?>
		<div id="mcs-widget"></div>
		<?php
	}

	public function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			[
				'title' => 'Please select your location',
				'mode'  => 0,
			]
		);
		?>
		<p>
			<label for="<?= $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
			<input class="widefat" id="<?= $this->get_field_id( 'title' ); ?>"
				   name="<?= $this->get_field_name( 'title' ); ?>" type="text"
				   value="<?php echo esc_attr( $instance['title'] ); ?>"/>
		</p>
		<p>
			<label for="<?php echo $this->get_field_id( 'mode' ); ?>"><?php _e( 'Mode:' ); ?></label>
			<select class="widefat" id="<?php echo $this->get_field_id( 'mode' ); ?>"
					name="<?php echo $this->get_field_name( 'mode' ); ?>">
				<?php foreach ( $this->modes as $modeId => $modeTitle ) : ?>
					<option
						value="<?= esc_attr( $modeId ); ?>" <?php selected( $modeId, $instance['mode'] ); ?>>
						<?= esc_html( $modeTitle ); ?>
					</option>
				<?php endforeach; ?>
			</select>
		</p>
		<?php
	}

	public function update( $new_instance, $old_instance ) {
		$instance          = [];
		$instance['title'] = sanitize_text_field( $new_instance['title'] );
		$instance['mode']  = (int) $new_instance['mode'];

		return $instance;
	}
}
