<?php
/**
 * The template for displaying the testimonial archive pages.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package billie
 */


get_header(); ?>

	<?php $jetpack_options = get_theme_mod( 'jetpack_testimonials' ); ?>

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">


	<?php if ( '' != $jetpack_options['featured-image'] ) : ?>
			<?php echo wp_get_attachment_image( (int)$jetpack_options['featured-image'], 'billie-jetpack-logo' ); ?>
	<?php endif; ?>


		<?php if ( have_posts() ) : ?>

			<header class="page-header">
				<h1 class="page-title">
					<?php
						if ( '' != $jetpack_options['page-title'] )
							echo esc_html( $jetpack_options['page-title'] );
						else
							_e( 'Testimonials', 'billie' );
						?>
				</h1>
			<?php
				if ( '' != $jetpack_options['page-content'] ) :
			?>
					<?php echo convert_chars( convert_smilies( wptexturize( stripslashes( wp_filter_post_kses( addslashes( $jetpack_options['page-content'] ) ) ) ) ) ); ?>

			<?php endif;
			?>

			</header><!-- .page-header -->

		

			<?php while ( have_posts() ) : the_post(); ?>

				<?php
					/* Include the Post-Format-specific template for the content.
					 * If you want to override this in a child theme, then include a file
					 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
					 */
					 get_template_part( 'content', 'testimonial-single' ); 
				?>

			<?php endwhile; ?>
		<?php endif; ?>

		</main><!-- #main -->
	</div><!-- #primary -->

<?php 
get_footer();
?>