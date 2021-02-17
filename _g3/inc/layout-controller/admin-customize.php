<?php

add_action( 'customize_register', 'lightning_customize_register_panel' );
function lightning_customize_register_panel( $wp_customize ) {
	/*
	  Layout Panel
	 /*-------------------------------------------*/
	$wp_customize->add_panel(
		'lightning_layout',
		array(
			'priority'       => 503,
			'capability'     => 'edit_theme_options',
			'theme_supports' => '',
			'title'          => __( 'Lightning Layoout Settings', 'vk-all-in-one-expansion-unit' ),
		)
	);
}