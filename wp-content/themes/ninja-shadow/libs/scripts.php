<?php
/**
 * Enqueue scripts and styles.
 *
 */
 
// 	Enqueue styles
	function ninja_shadow_styles() {
		
		wp_enqueue_style( 'normalize', get_template_directory_uri().'/assets/css/normalize.css' );
		wp_enqueue_style( 'skeleton', get_template_directory_uri().'/assets/css/skeleton.css' );
		wp_enqueue_style( 'defalt', get_template_directory_uri().'/assets/css/skins/defalt.css' );
		wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/assets/font-awesome/css/font-awesome.min.css' );
		wp_enqueue_style( 'animate', get_template_directory_uri().'/assets/css/animate.css' );
		wp_enqueue_style( 'fonts', get_template_directory_uri().'/assets/css/fonts.css' );
		wp_enqueue_style( 'base', get_template_directory_uri().'/assets/css/base.css' );
		wp_enqueue_style( 'theme-style', get_template_directory_uri().'/assets/css/theme-style.css' );
		wp_enqueue_style( 'responsive', get_template_directory_uri().'/assets/css/responsive.css' );
		wp_enqueue_style( 'customscores-style', get_stylesheet_uri() );
		wp_enqueue_style( 'custom', get_template_directory_uri().'/assets/css/custom.css' );
	}
	add_action( 'wp_enqueue_scripts', 'ninja_shadow_styles' );
	

// 	Enqueue scripts

	function ninja_shadow_scripts() {
		wp_enqueue_script( 'customscores-navigation', get_template_directory_uri() . '/assets/js/navigation.js', array(), '20120206', true );

		wp_enqueue_script( 'customscores-skip-link-focus-fix', get_template_directory_uri() . '/assets/js/skip-link-focus-fix.js', array(), '20130115', true );

		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
	add_action( 'wp_enqueue_scripts', 'ninja_shadow_scripts' );

//	Enqueue fonts
	function ninja_shadow_fonts() {
		
		// Raleway
		wp_enqueue_style( 'prefix_Raleway', '//fonts.googleapis.com/css?family=Raleway:400,100,300,700', array(), null, 'screen' );
		
		// Roboto
		wp_enqueue_style( 'prefix_Roboto', '//fonts.googleapis.com/css?family=Roboto:400,300,100,700,900,300italic', array(), null, 'screen' );
		
		// Source Sans Pro
		wp_enqueue_style( 'prefix_source_sans', '//fonts.googleapis.com/css?family=Roboto+Slab:400,100,300,700', array(), null, 'screen' );
		
	}
	add_action('wp_print_styles', 'ninja_shadow_fonts');
	
	