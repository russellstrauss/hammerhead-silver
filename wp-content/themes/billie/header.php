<?php
/**
 * The header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="content">
 *
 * @package billie
 */
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="viewport" content="width=device-width" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<div id="page" class="hfeed site">
	<a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'billie' ); ?></a>
	<header id="masthead" class="site-header" role="banner">

	<?php if ( has_nav_menu( 'header' )  ) {
	?>
		<nav id="site-navigation" class="main-navigation" role="navigation">
			<button class="menu-toggle" aria-controls="menu" aria-expanded="false"><span class="screen-reader-text"><?php _e( 'Main Menu', 'billie' ); ?></span></button>
			<?php wp_nav_menu( array( 'theme_location' => 'header', 'fallback_cb' => false, 'depth'=>2 ) );  ?>
		</nav><!-- #site-navigation -->
	<?php
	}
	?>
	<div class="site-branding">	
		<?php //billie_the_site_logo(); ?>
		
		<img id="site-logo" src="<?php header_image(); ?>" alt="logo" />
		
		<?php if (display_header_text() ) {	?>
			<?php if (get_bloginfo('description') <> '') {	?>
				<div class="site-description"><?php bloginfo( 'description' ); ?></div>
			<?php }	?>

			<h1 class="site-title"><?php bloginfo( 'name' ); ?></h1>
		<?php }else{ /*If there is no visible site title, make sure there is still a h1 for screen reader*/	?>
				<h1 class="screen-reader-text"><?php bloginfo( 'name' ); ?></h1>
		<?php } ?>

		<?php if( !get_theme_mod( 'billie_hide_action' ) ) {
					if( get_theme_mod( 'billie_action_text' ) ) {	
						echo '<div id="action">';
						if( get_theme_mod( 'billie_action_link' ) ) {
							echo '<a href="' . esc_url( get_theme_mod( 'billie_action_link' ) ) .'">';
						}
						echo esc_html( get_theme_mod( 'billie_action_text' ) );
						if( get_theme_mod( 'billie_action_link' )) {
							echo '</a>';
						}
						echo '</div>';
					}else{
						if ( is_user_logged_in() ) {
							echo '<div id="action">';
							echo '<a href="' . esc_url( home_url( '/wp-admin/customize.php' ) ) . '">' . __("Click here to setup your Call to Action", 'billie') . '</a>';
							echo '</div>';
						}
					}
					?>
			<?php
		}
			if ( !get_theme_mod('billie_hide_search') ){
				get_search_form();
			}
			?>

		</div><!-- .site-branding -->
			
	</header><!-- #masthead -->
	
	<div id="content" class="site-content">