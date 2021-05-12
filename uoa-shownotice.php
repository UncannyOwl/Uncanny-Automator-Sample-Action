<?php

use Uncanny_Automator\Recipe;

/**
 * Class UOA_SHOWNOTICE
 */
class UOA_SHOWNOTICE {
	use Recipe\Actions;

	/**
	 * UOA_SHOWNOTICE constructor.
	 */
	public function __construct() {
		$this->setup_action();
	}

	/**
	 *
	 */
	protected function setup_action() {
		$this->set_author( 'Developer name' );
		$this->set_support_link( 'https://automatorplugin.com/knowledge-base/?utm_source=' ); // link to your KB
		$this->set_integration( 'UOA' );
		$this->set_action_meta( 'SHOWNOTICE' );
		$this->set_action_code( 'UOASHOWNOTICE' );
		/* translators: Action - WordPress */
		$this->set_sentence( sprintf( esc_attr__( 'Show a {{message:%1$s}} on a page', 'uncanny-automator' ), $this->get_action_meta() ) );
		/* translators: Action - WordPress */
		$this->set_readable_sentence( esc_attr__( 'Show a {{message}} on a page', 'uncanny-automator' ) );
		$option = array(
			Automator()->helpers->recipe->field->text(
				array(
					'option_code' => $this->get_action_meta(),
					'label'       => esc_attr__( 'Message', 'uncanny-automator' ),
					'input_type'  => 'text',
				)
			),
		);

		$this->set_options( $option );

		$this->register_action();
	}


	/**
	 * @param int $user_id
	 * @param array $action_data
	 * @param int $recipe_id
	 * @param array $args
	 * @param $parsed
	 */
	protected function process_action( int $user_id, array $action_data, int $recipe_id, array $args, $parsed ) {
		$message = isset( $parsed[ $this->get_action_meta() ] ) ? $parsed[ $this->get_action_meta() ] : '';
		if ( ! is_page() ) {
			Automator()->complete->action( $user_id, $action_data, $recipe_id, 'User was not on a page' );
		}
		$message = trim( wp_strip_all_tags( $message ) );
		ob_start();
		?>
        <script>
            var t = setTimeout(function () {
                alert('<?php echo esc_html( $message ); ?>');
            }, 1000);
        </script>
		<?php
		echo ob_get_clean();
		Automator()->complete->action( $user_id, $action_data, $recipe_id );
	}
}
