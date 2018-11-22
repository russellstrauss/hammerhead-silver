<?php
/**
 * Template Name: Shop Front
 *
 * @package billie
 */
?>

<?php get_header(); ?>

<div id="primary" class="about-page content-area">
	<main id="main" class="site-main" role="main">

		<div class="shop-front">
			
			<div class="profile-pics">
				<a href="<?php echo get_site_url(); ?>/index.php/product-category/dustin/">
					<div class="jewelry-page-link dustin" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/dustin-page-photo.jpg);">
						<div class="overlay"></div>
						<div class="text">Dustin</div>
					</div>
				</a>
				
				<a href="<?php echo get_site_url(); ?>/index.php/product-category/hillary/">
					<div class="jewelry-page-link hillary" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/hillary-page-photo.jpg);">
						<div class="overlay"></div>
						<div class="text">Hillary</div>
					</div>
				</a>
			</div>
			
			<a href="<?php echo get_site_url(); ?>/index.php/shop/">
				<div class="jewelry-page-link all" style="background-image: url(<?php echo get_template_directory_uri(); ?>/assets/img/all-jewelry.jpg);">
					<div class="overlay"></div>
					<div class="text">all jewelry</div>
				</div>
			</a>
		</div>

	</main>
</div>

<?php get_footer(); ?>
