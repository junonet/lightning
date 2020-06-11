<?php
/**
 * Layout Controller of Lightning.
 *
 * @package Lightning
 */

/**
 * Array of Layout Taeget
 */
function lightning_layout_target_array() {
	$array = array(
		'error404' => array(
			'function' => 'is_404',
		),
		'search'   => array(
			'function' => 'is_search',
		),
	);
	return $array;
}

/**
 * Lightning Is Layout One Column
 *
 * @since Lightning 9.0.0
 * @return boolean
 */
function lightning_is_layout_onecolumn() {
	$onecolumn = false;
	$options   = get_option( 'lightning_theme_options' );
	global $wp_query;

	$array = lightning_layout_target_array();

	foreach ( $array as $key => $value ) {
		if ( call_user_func( $value['function'] ) ) {
			if ( isset( $options['layout'][ $key ] ) ) {
				if ( 'col-one' === $options['layout'][ $key ] || 'col-one-no-subsection' === $options['layout'][ $key ] ) {
					$onecolumn = true;
				}
			}
		}
	}

	if ( is_front_page() ) {
		if ( isset( $options['layout']['front-page'] ) ) {
			if ( 'col-one' === $options['layout']['front-page'] || 'col-one-no-subsection' === $options['layout']['front-page'] ) {
				$onecolumn = true;
			}
		} else {
			$page_on_front_id = get_option( 'page_on_front' );
			if ( $page_on_front_id ) {
				$template = get_post_meta( $page_on_front_id, '_wp_page_template', true );
				if ( 'page-onecolumn.php' === $template || 'page-lp.php' === $template ) {
					$onecolumn = true;
				}
			}
		}
	} elseif ( is_home() && ! is_front_page() ) {
		if ( isset( $options['layout']['archive'] ) ) {
			if ( $options['layout']['archive'] === 'col-one' || $options['layout']['archive'] === 'col-one-no-subsection' ) {
				$onecolumn = true;
			}
		}
		if ( isset( $options['layout']['archive-post'] ) ) {
			if ( 'col-one' === $options['layout']['archive-post'] || 'col-one-no-subsection' === $options['layout']['archive-post'] ) {
				$onecolumn = true;
			}
		}
	}

	$get_post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		),
		'names'
	);

	if ( is_archive() ) {
		if ( isset( $options['layout']['archive'] ) ) {
			if ( 'col-one' === $options['layout']['archive'] || 'col-one-no-subsection' === $options['layout']['archive'] ) {
				$onecolumn = true;
			}
		}
		$get_post_types = array( 'post' ) + $get_post_types;
		foreach ( $get_post_types as $get_post_type ) {
			if ( isset( $options['layout'][ 'archive-' . $get_post_type ] ) && get_post_type() === $get_post_type ) {
				if ( 'col-one' === $options['layout'][ 'archive-' . $get_post_type ] || 'col-one-no-subsection' === $options['layout'][ 'archive-' . $get_post_type ] ) {
					$onecolumn = true;
				}
			}
		}
	}

	if ( is_singular() ) {
		global $post;
		if ( is_page() ) {
			$template           = get_post_meta( $post->ID, '_wp_page_template', true );
			$template_onecolumn = array(
				'page-onecolumn.php',
				'page-lp.php',
			);
			if ( in_array( $template, $template_onecolumn, true ) ) {
				$onecolumn = true;
			}
		}
		if ( is_page() && isset( $options['layout']['page'] ) ) {
			if ( 'col-one' === $options['layout']['page'] || 'col-one-no-subsection' === $options['layout']['page'] ) {
				$onecolumn = true;
			}
		}
		if ( is_single() && isset( $options['layout']['single'] ) ) {
			if ( 'col-one' === $options['layout']['single'] || 'col-one-no-subsection' === $options['layout']['single'] ) {
				$onecolumn = true;
			}
		}
		$get_post_types = array( 'post', 'page' ) + $get_post_types;
		foreach ( $get_post_types as $get_post_type ) {
			if ( isset( $options['layout'][ 'single-' . $get_post_type ] ) && get_post_type() === $get_post_type ) {
				if ( 'col-one' === $options['layout'][ 'single-' . $get_post_type ] || 'col-one-no-subsection' === $options['layout'][ 'single-' . $get_post_type ] ) {
					$onecolumn = true;
				}
			}
		}
		if ( isset( $post->_lightning_design_setting['layout'] ) ) {
			if ( 'col-two' === $post->_lightning_design_setting['layout'] ) {
				$onecolumn = false;
			} elseif ( 'col-one-no-subsection' === $post->_lightning_design_setting['layout'] ) {
				$onecolumn = true;
			} elseif ( 'col-one' === $post->_lightning_design_setting['layout'] ) {
				$onecolumn = true;
			}
		}
	}
	return apply_filters( 'lightning_is_layout_onecolumn', $onecolumn );
}

/**
 * Lightning Is Subsection Display
 *
 * @since Lightning 9.0.0
 * @return boolean
 */
function lightning_is_subsection_display() {
	global $post;
	$return  = true;
	$options = get_option( 'lightning_theme_options' );

	$array = lightning_layout_target_array();

	foreach ( $array as $key => $value ) {
		if ( call_user_func( $value['function'] ) ) {
			if ( isset( $options['layout'][ $key ] ) ) {
				if ( 'col-one-no-subsection' === $options['layout'][ $key ] ) {
					$onecolumn = false;
				}
			}
		}
	}

	$get_post_types = get_post_types(
		array(
			'public'   => true,
			'_builtin' => false,
		),
		'names'
	);

	// break and hidden.
	if ( is_front_page() && ! is_home() ) {
		if ( isset( $options['layout']['front-page'] ) && 'col-one-no-subsection' === $options['layout']['front-page'] ) {
			$return = false;
		}
	} elseif ( is_home() ) {
		if ( isset( $options['layout']['archive'] ) ) {
			if ( $options['layout']['archive'] === 'col-one-no-subsection' ) {
				$return = false;
			}
		}
		if ( isset( $options['layout']['archive-post'] ) ) {
			if ( 'col-one-no-subsection' === $options['layout']['archive-post'] ) {
				$return = false;
			}
		}
	} elseif ( is_archive() ) {
		if ( isset( $options['layout']['archive'] ) ) {
			if ( 'col-one-no-subsection' === $options['layout']['archive'] ) {
				$return = false;
			}
		}
		$get_post_types = array( 'post' ) + $get_post_types;
		foreach ( $get_post_types as $get_post_type ) {
			if ( isset( $options['layout'][ 'archive-' . $get_post_type ] ) && get_post_type() === $get_post_type ) {
				if ( 'col-one-no-subsection' === $options['layout'][ 'archive-' . $get_post_type ] ) {
					$return = false;
				}
			}
		}
	} elseif ( is_singular() ) {
		if ( is_single() && isset( $options['layout']['single'] ) ) {
			if ( 'col-one-no-subsection' === $options['layout']['single'] ) {
				$return = false;
			}
		}
		if ( is_page() && isset( $options['layout']['page'] ) ) {
			if ( 'col-one-no-subsection' === $options['layout']['page'] ) {
				$return = false;
			}
		}
		$get_post_types = array( 'post', 'page' ) + $get_post_types;
		foreach ( $get_post_types as $get_post_type ) {
			if ( isset( $options['layout'][ 'single-' . $get_post_type ] ) && get_post_type() === $get_post_type ) {
				if ( 'col-one-no-subsection' === $options['layout'][ 'single-' . $get_post_type ] ) {
					$return = false;
				}
			}
		}
		if ( isset( $post->_lightning_design_setting['layout'] ) ) {
			if ( 'col-one-no-subsection' === $post->_lightning_design_setting['layout'] ) {
				$return = false;
			}
		}
	}
	return apply_filters( 'lightning_is_subsection_display', $return );
}

/**
 * Page header and Breadcrumb Display or hidden
 *
 * @since Lightning 9.0.0
 * @return boolean
 */
function lightning_is_page_header_and_breadcrumb() {
	$return = true;
	if ( is_singular() ) {
		global $post;

		if ( ! empty( $post->_lightning_design_setting['hidden_page_header_and_breadcrumb'] ) ) {
			$return = false;
		}
	}
	return apply_filters( 'lightning_is_page_header_and_breadcrumb', $return );
}

function lightning_is_siteContent_padding_off() {
	if ( is_singular() ) {
		global $post;
		$cf = $post->_lightning_design_setting;
		if ( ! empty( $cf['siteContent_padding'] ) ) {
			return true;
		}
	}
}
