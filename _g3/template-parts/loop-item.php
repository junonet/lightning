<?php
$options = array(
	'layout'                     => 'media', // card , card-horizontal , media
	'display_image'              => true,
	'display_image_overlay_term' => true,
	'display_excerpt'            => true,
	'display_date'               => true,
	'display_new'                => true,
	'display_btn'                => true,
	'image_default_url'          => false,
	'overlay'                    => false,
	'btn_text'                   => __( 'Read more', 'lightning' ),
	'btn_align'                  => 'text-right',
	'new_text'                   => __( 'New!!', 'lightning' ),
	'new_date'                   => 7,
	'class_outer'                => 'vk_post-col-sm-12 vk_post-col-lg-12',
	'class_title'                => '',
	'body_prepend'               => '',
	'body_append'                => '',
);
wp_kses_post( VK_Component_Posts::the_view( $post, $options ) );
