<?php
/**
 * The header for our theme.
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="profile" href="http://gmpg.org/xfn/11">
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">

	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<div class="wrapper">
	<div class="wrapper-container">
	
		<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'customscores' ); ?></a>
		<!-- Header -->
		<div class="header">
			<div class="header-container">
				<div class="top-header">
					<div class="container">
						<div class="logo animated fadeIn">
							<a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a>
						</div>
						<div class="tagline">
							<?php bloginfo( 'description' ); ?>
						</div>
					</div>
				</div>
				<div class="middle-header">
					<div class="container">
						<div class="main-menu">
							<nav id="site-navigation" class="main-navigation" role="navigation">
								<button class="menu-toggle" aria-controls="menu" aria-expanded="false">
									<?php _e( 'Primary Menu', 'ninja-shadow' ); ?>
								</button>
								<?php wp_nav_menu( array( 'theme_location' => 'primary' ) ); ?>
							</nav>
						</div>
					</div>
				</div>
				<div class="bottom-header">
				</div>
			</div>
		</div><!-- /header -->
