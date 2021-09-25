<?php
/**
 * Class Lightning_G3_Key_Color_To_Manager_Update test
 *
 * @package lightning
 */

/**
 * Sample test case.
 */
class Lightning_G3_Key_Color_To_Manager_Update_Test extends WP_UnitTestCase {

	/**
	 * Lightning_g3_key_color_to_manager test
	 *
	 * @return void
	 */
	public function test_lightning_g3_key_color_to_manager_update() {
		$test_array = array(
			array(
				'lightning_theme_options'  => null,
				'vk_color_manager_options' => null,
				'correct'                  => '#337ab7',
			),
			array(
				'lightning_theme_options'  => array(
					'color_key' => '#ff0000',
				),
				'vk_color_manager_options' => null,
				'correct'                  => '#ff0000',
			),
			array(
				'lightning_theme_options'  => array(
					'color_key' => '#ff0000',
				),
				'vk_color_manager_options' => '',
				'correct'                  => '#ff0000',
			),
			array(
				'lightning_theme_options'  => array(
					'color_key' => '#ff0000',
				),
				'vk_color_manager_options' => array(
					'color_custom_1' => '#0000ff',
				),
				'correct'                  => '#0000ff',
			),
		);
		print PHP_EOL;
		print '------------------------------------' . PHP_EOL;
		print 'lightning_g3_key_color_to_manager_update()' . PHP_EOL;
		print '------------------------------------' . PHP_EOL;

		foreach ( $test_array as $key => $value ) {
			update_option( 'lightning_theme_options', $value['lightning_theme_options'] );
			update_option( 'vk_color_manager_options', $value['vk_color_manager_options'] );
			lightning_g3_key_color_to_manager_update();
			$vk_color_manager_options = get_option( 'vk_color_manager_options' );
			$return                   = $vk_color_manager_options['color_custom_1'];

			print 'return  :' . esc_html( $return ) . PHP_EOL;
			print 'correct :' . esc_html( $value['correct'] ) . PHP_EOL;
			$this->assertEquals( $value['correct'], $return );
		}
	}

}
