<?php
/**
 * The Leader Theme Customizer
 *
 * @package The_Leader
 */

/**
 * Add postMessage support for site title and description for the Theme Customizer.
 *
 * @param WP_Customize_Manager $wp_customize Theme Customizer object.
 */
function the_leader_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport         = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport  = 'postMessage';
	$wp_customize->get_setting( 'header_textcolor' )->transport = 'postMessage';
	
	// Adding setting for all the anchor tags in the website.
	$wp_customize->add_setting(
		'the_leader_link_color',
		array(
			'default' => '#000000',
			'transport' => 'postMessage'
		)
	);
	$wp_customize->add_control(
		new WP_Customize_Color_Control(
			$wp_customize,
			'link_color',
			array(
				'label'      => __( 'Link Color', 'the_leader' ),
                'section'    => 'colors',
                'settings'   => 'the_leader_link_color'
			)
		)
	);

	/**
	 * Working on a new section for learning purposes, might change this in future,
	 * This will be aligned with the theme customization goals.
	 **/
	$wp_customize->add_section(
		'the_leader_header_options',
		array(
			'title' => __('Display Options', 'the_leader'),
			'priority' => 200
		)
	);

	$wp_customize->add_setting(
		'the_leader_display_option',
		array(
			'default' => true,
			'transport' => 'postMessage'
		)
	);

	$wp_customize->add_control(
		'the_leader_display_option',
		array(
			'section' => 'the_leader_header_options',
			'label' => __('Display Header ?', 'the_leader'),
			'type' => 'checkbox'
		)
	);
}
add_action( 'customize_register', 'the_leader_customize_register' );

function the_leader_customizer_css() {
    ?>
    <style type="text/css">
        a { color: <?php echo get_theme_mod( 'the_leader_link_color' ); ?>; }
       	#header { display: none; }
    </style>
    <?php
}
add_action( 'wp_head', 'the_leader_customizer_css' );

/**
 * Binds JS handlers to make Theme Customizer preview reload changes asynchronously.
 */
function the_leader_customize_preview_js() {
	wp_enqueue_script( 'the_leader_customizer', get_template_directory_uri() . '/js/customizer.js', array( 'customize-preview' ), '20151215', true );
}
add_action( 'customize_preview_init', 'the_leader_customize_preview_js' );
