<?php
/**
 * myStore functions and definitions
 *
 * @package myStore
 */
define( 'MYSTORE_THEME_VERSION' , '1.0.7' );

if ( ! function_exists( 'mystore_setup' ) ) :
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function mystore_setup() {
	
	/**
	 * Set the content width based on the theme's design and stylesheet.
	 */
	global $content_width;
	if ( ! isset( $content_width ) ) {
		$content_width = 900; /* pixels */
	}

	/*
	 * Make theme available for translation.
	 * Translations can be filed in the /languages/ directory.
	 * If you're building a theme based on myStore, use a find and replace
	 * to change 'mystore' to the name of your theme in all the template files
	 */
	load_theme_textdomain( 'mystore', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
	 * Let WordPress manage the document title.
	 * By adding theme support, we declare that this theme does not use a
	 * hard-coded <title> tag in the document head, and expect WordPress to
	 * provide it for us.
	 */
	add_theme_support( 'title-tag' );

	/*
	 * Enable support for Post Thumbnails on posts and pages.
	 *
	 * @link http://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
	 */
	add_theme_support( 'post-thumbnails' );
	add_image_size( 'mystore_blog_img_side', 500, 380, true );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => esc_html__( 'Primary Menu', 'mystore' ),
        'top-bar-menu' => __( 'Top Bar Menu', 'mystore' )
	) );

	/*
	 * Switch default core markup for search form, comment form, and comments
	 * to output valid HTML5.
	 */
	add_theme_support( 'html5', array(
		'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
	) );

	/*
	 * Enable support for Post Formats.
	 * See http://codex.wordpress.org/Post_Formats
	 */
	add_theme_support( 'post-formats', array(
		'aside', 'image', 'video', 'quote', 'link',
	) );
	
	// The custom header is used for the logo
	add_theme_support( 'custom-header', array(
        'default-image' => '',
		'width'         => 300,
		'height'        => 100,
		'flex-width'    => false,
		'flex-height'   => true,
		'header-text'   => false,
	) );

	// Set up the WordPress core custom background feature.
	add_theme_support( 'custom-background', apply_filters( 'mystore_custom_background_args', array(
		'default-color' => 'F9F9F9',
		'default-image' => '',
	) ) );
	
	add_theme_support( 'woocommerce' );
}
endif; // mystore_setup
add_action( 'after_setup_theme', 'mystore_setup' );

/**
 * Register widget area.
 *
 * @link http://codex.wordpress.org/Function_Reference/register_sidebar
 */
function mystore_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'mystore' ),
		'id'            => 'sidebar-1',
		'description'   => '',
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => '</aside>',
		'before_title'  => '<h3 class="widget-title">',
		'after_title'   => '</h3>',
	) );
	
	register_sidebar(array(
		'name' => __( 'Sidebar Menu', 'mystore' ),
		'id' => 'mystore-sidebar-menu',
        'description' => __( 'These widgets are placed in the slide out menu under the navigation.', 'mystore' )
	));
	
	register_sidebar(array(
		'name' => __( 'myStore Footer Standard', 'mystore' ),
		'id' => 'mystore-site-footer-standard',
        'description' => __( 'The footer will divide into however many widgets are placed here.', 'mystore' )
	));
}
add_action( 'widgets_init', 'mystore_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function mystore_scripts() {
	wp_enqueue_style( 'mystore-google-body-font-default', '//fonts.googleapis.com/css?family=Open+Sans:400,300,300italic,400italic,600,600italic,700,700italic', array(), MYSTORE_THEME_VERSION );
	wp_enqueue_style( 'mystore-google-heading-font-default', '//fonts.googleapis.com/css?family=Droid+Serif:400,700,400italic,700italic', array(), MYSTORE_THEME_VERSION );
	
	wp_enqueue_style( 'font-awesome', get_template_directory_uri().'/includes/font-awesome/css/font-awesome.css', array(), '4.3.0' );
	
	if ( get_theme_mod( 'mystore-header-layout' ) == 'mystore-header-layout-standard' ) :
		wp_enqueue_style( 'mystore-header-style-standard', get_template_directory_uri().'/templates/css/mystore-header-standard.css', array(), MYSTORE_THEME_VERSION );
	else :
		wp_enqueue_style( 'mystore-header-style-centered', get_template_directory_uri().'/templates/css/mystore-header-centered.css', array(), MYSTORE_THEME_VERSION );
	endif;
	
	wp_enqueue_style( 'mystore-style', get_stylesheet_uri(), array(), MYSTORE_THEME_VERSION );
	
	if ( mystore_is_woocommerce_activated() ) :
		
		wp_enqueue_style( 'mystore-standard-woocommerce-style', get_template_directory_uri().'/templates/css/mystore-woocommerce-standard-style.css', array(), MYSTORE_THEME_VERSION );
	
	endif;
	
	wp_enqueue_style( 'mystore-footer-standard-style', get_template_directory_uri().'/templates/css/mystore-footer-standard.css', array(), MYSTORE_THEME_VERSION );

	wp_enqueue_script( 'caroufredsel', get_template_directory_uri() . '/js/jquery.carouFredSel-6.2.1-packed.js', array('jquery'), MYSTORE_THEME_VERSION, true );
	
	wp_enqueue_script( 'mystore-customjs', get_template_directory_uri() . '/js/custom.js', array('jquery'), MYSTORE_THEME_VERSION, true );
	
	wp_enqueue_script( 'mystore-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), MYSTORE_THEME_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'mystore_scripts' );

// Add the favicon to the header if set
function mystore_site_favicon() {
    if ( get_theme_mod( 'mystore-header-favicon', false ) ) :
        echo '<link rel="icon" href="' . esc_url( get_theme_mod( 'mystore-header-favicon' ) ) . '">';
    endif;
}
add_action('wp_head', 'mystore_site_favicon');

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/includes/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/includes/inc/extras.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/includes/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
require get_template_directory() . '/includes/inc/jetpack.php';

// Helper library for the theme customizer.
require get_template_directory() . '/customizer/customizer-library/customizer-library.php';

// Define options for the theme customizer.
require get_template_directory() . '/customizer/customizer-options.php';

// Output inline styles based on theme customizer selections.
require get_template_directory() . '/customizer/styles.php';

// Additional filters and actions based on theme customizer selections.
require get_template_directory() . '/customizer/mods.php';

/**
 * Enqueue myStore custom customizer styling.
 */
function mystore_load_customizer_script() {
    wp_enqueue_script( 'mystore-customizer-js', get_template_directory_uri() . '/customizer/customizer-library/js/customizer-custom.js', array('jquery'), MYSTORE_THEME_VERSION, true );
    
    wp_enqueue_style( 'mystore-customizer-css', get_template_directory_uri() . '/customizer/customizer-library/css/customizer.css' );
}
add_action( 'customize_controls_enqueue_scripts', 'mystore_load_customizer_script' );

/* Display the recommended plugins notice that can be dismissed */
add_action('admin_notices', 'mystore_recommended_plugin_notice');

function mystore_recommended_plugin_notice() {
    global $pagenow;
    global $current_user;
    
    $user_id = $current_user->ID;
    
    /* If on plugins page, check that the user hasn't already clicked to ignore the message */
    if ( $pagenow == 'plugins.php' ) {
	    if ( ! get_user_meta( $user_id, 'mystore_recommended_plugin_ignore_notice' ) ) {
	        echo '<div class="updated"><p>';
            printf( __('<p>Install the plugins we at <a href="http://www.kairaweb.com/" target="_blank">Kaira</a> recommended | <a href="%1$s">Hide Notice</a></p>', 'mystore' ), '?mystore_recommended_plugin_nag_ignore=0' ); ?>
            <a href="<?php echo admin_url('plugin-install.php?tab=favorites&user=kaira'); ?>"><?php printf( __( 'WooCommerce', 'mystore' ), 'mystore' ); ?></a><br />
            <a href="<?php echo admin_url('plugin-install.php?tab=favorites&user=kaira'); ?>"><?php printf( __( 'SiteOrigin\'s Page Builder', 'mystore' ), 'mystore' ); ?></a><br />
            <a href="<?php echo admin_url('plugin-install.php?tab=favorites&user=kaira'); ?>"><?php printf( __( 'Meta Slider', 'mystore' ), 'mystore' ); ?></a><br />
            <a href="<?php echo admin_url('plugin-install.php?tab=favorites&user=kaira'); ?>"><?php printf( __( 'Breadcrumb NavXT', 'mystore' ), 'mystore' ); ?></a>
            <?php
	        echo "</p></div>";
	    }
	}
}
add_action('admin_init', 'mystore_recommended_plugin_nag_ignore');

function mystore_recommended_plugin_nag_ignore() {
    global $current_user;
    $user_id = $current_user->ID;
        
    /* If user clicks to ignore the notice, add that to their user meta */
    if ( isset($_GET['mystore_recommended_plugin_nag_ignore']) && '0' == $_GET['mystore_recommended_plugin_nag_ignore'] ) {
        add_user_meta( $user_id, 'mystore_recommended_plugin_ignore_notice', 'true', true );
    }
}

// Add specific CSS class by filter
function mystore_add_body_class( $classes ) {
	if ( get_theme_mod( 'mystore-page-styling' ) ) {
		$page_style_class = get_theme_mod( 'mystore-page-styling' );
	} else {
		$page_style_class = 'mystore-page-styling-flat';
	}
	$classes[] = $page_style_class;
	
	return $classes;
}
add_filter( 'body_class', 'mystore_add_body_class' );

// Create function to check if WooCommerce exists.
if ( ! function_exists( 'mystore_is_woocommerce_activated' ) ) :
    
function mystore_is_woocommerce_activated() {
    if ( class_exists( 'woocommerce' ) ) { return true; } else { return false; }
}

endif; // mystore_is_woocommerce_activated

if ( mystore_is_woocommerce_activated() ) {
	require get_template_directory() . '/includes/inc/woocommerce-header-inc.php';
}

/**
 * Adjust is_home query if mystore-blog-cats is set
 */
function mystore_set_blog_queries( $query ) {
    $blog_query_set = '';
    if ( get_theme_mod( 'mystore-blog-cats', false ) ) {
        $blog_query_set = get_theme_mod( 'mystore-blog-cats' );
    }
    
    if ( $blog_query_set ) {
        // do not alter the query on wp-admin pages and only alter it if it's the main query
        if ( !is_admin() && $query->is_main_query() ){
            if ( is_home() ){
                $query->set( 'cat', $blog_query_set );
            }
        }
    }
}
add_action( 'pre_get_posts', 'mystore_set_blog_queries' );

/**
 * Display the upgrade to Premium page & load styles.
 *
 * @action admin_menu
 */
function mystore_admin_menu() {
    global $mystore_upgrade_page;
    $mystore_upgrade_page = add_theme_page( __( 'MYSTORE Premium', 'mystore' ), __( 'MYSTORE Premium', 'mystore' ), 'edit_theme_options', 'premium_upgrade', 'mystore_upgrade_page_render' );
}

add_action( 'admin_menu', 'mystore_admin_menu' );

/**
 * Render the theme upgrade page
 */
function mystore_upgrade_page_render() {
    locate_template( 'upgrade/kaira-upgrade-page.php', true, false );
}

/**
 * Enqueue myStore admin stylesheet only on upgrade page.
 */
function mystore_load_admin_style($hook) {
    global $mystore_upgrade_page;
 
    if( $hook != $mystore_upgrade_page ) 
        return;
    
    wp_enqueue_style( 'mystore-upgrade-css', get_template_directory_uri() . '/upgrade/css/kaira-admin.css' );
}    
add_action( 'admin_enqueue_scripts', 'mystore_load_admin_style' );