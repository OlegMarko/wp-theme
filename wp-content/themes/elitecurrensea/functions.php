<?php
/**
 * elitecurrensea functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package elitecurrensea
 */

if ( ! function_exists( 'elitecurrensea_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function elitecurrensea_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on elitecurrensea, use a find and replace
		 * to change 'elitecurrensea' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'elitecurrensea', get_template_directory() . '/languages' );

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
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );

		// This theme uses wp_nav_menu() in one location.
		register_nav_menus( array(
			'menu-1' => esc_html__( 'Primary', 'elitecurrensea' ),
		) );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support( 'html5', array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
		) );

		// Set up the WordPress core custom background feature.
		add_theme_support( 'custom-background', apply_filters( 'elitecurrensea_custom_background_args', array(
			'default-color' => 'ffffff',
			'default-image' => '',
		) ) );

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		/**
		 * Add support for core custom logo.
		 *
		 * @link https://codex.wordpress.org/Theme_Logo
		 */
		add_theme_support( 'custom-logo', array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		) );
	}
endif;
add_action( 'after_setup_theme', 'elitecurrensea_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function elitecurrensea_content_width() {
	// This variable is intended to be overruled from themes.
	// Open WPCS issue: {@link https://github.com/WordPress-Coding-Standards/WordPress-Coding-Standards/issues/1043}.
	// phpcs:ignore WordPress.NamingConventions.PrefixAllGlobals.NonPrefixedVariableFound
	$GLOBALS['content_width'] = apply_filters( 'elitecurrensea_content_width', 640 );
}
add_action( 'after_setup_theme', 'elitecurrensea_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function elitecurrensea_widgets_init() {
	register_sidebar( array(
		'name'          => esc_html__( 'Sidebar', 'elitecurrensea' ),
		'id'            => 'sidebar-1',
		'description'   => esc_html__( 'Add widgets here.', 'elitecurrensea' ),
		'before_widget' => '<section id="%1$s" class="widget %2$s">',
		'after_widget'  => '</section>',
		'before_title'  => '<h2 class="widget-title">',
		'after_title'   => '</h2>',
	) );
}
add_action( 'widgets_init', 'elitecurrensea_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function elitecurrensea_scripts() {
	wp_enqueue_style( 'elitecurrensea-style', get_stylesheet_uri() );

	wp_enqueue_script( 'elitecurrensea-navigation', get_template_directory_uri() . '/js/navigation.js', array(), '20151215', true );

	wp_enqueue_script( 'elitecurrensea-skip-link-focus-fix', get_template_directory_uri() . '/js/skip-link-focus-fix.js', array(), '20151215', true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'elitecurrensea_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

class BootstrapNavMenuWalker extends Walker_Nav_Menu {
	
	function start_lvl( &$output, $depth = 0, $args = array() ) {
 
		$sub = $depth > 0 ? '-sub' : '';
		$indent = str_repeat( "\t", $depth );
		$div = $depth == 0 ? '<div class="sub-nav">' : '';
		$output	.= "\n$indent {$div}<ul class=\"menu-depth-{$depth} sub{$sub}-menu sub-nav-group\">\n";

	}

	function end_lvl( &$output, $depth = 0, $args = array() ) {

		$div = $depth == 0 ? '</div>' : '';
		$indent = str_repeat( "\t", $depth );
		$output .= "{$indent}</ul>{$div}\n";

	}
 
	function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {
	
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
		
		$li_attributes = '';
		$class_names = $value = '';
		
		// $classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'mega-menu-item';
		$classes[] = $depth > 0 ? 'sub-nav-item' : 'nav-item';
		$classes[] = 'menu-item-depth-' . $depth;
		$classes[] = ($item->current || $item->current_item_ancestor) ? 'current-menu-item' : '';
		$classes[] = ($args->has_children) ? 'has-submenu' : '';
 
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . '"';
		
		$id = apply_filters( 'nav_menu_item_id', 'nav-menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
		
		$output .= $indent . '<li' . $id . $value . $class_names . $li_attributes . '>';
		
		$attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target ) ? ' target="' . esc_attr( $item->target ) .'"' : '';
		$attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr( $item->xfn ) .'"' : '';
		$attributes .= ! empty( $item->url ) ? ' href="' . esc_attr( $item->url ) .'"' : '';
		$attributes .= $depth == 0 ? ' class="menu-link main-menu-link item-title"' : ' class="menu-link sub-menu-link"';
		
		$item_output = $args->before;
		$item_output .= '<a'. $attributes .'>';
		$item_output .= $args->link_before . ($depth == 0 ? '<span>' : '') . apply_filters( 'the_title', $item->title, $item->ID ) . ($depth == 0 ? '</span>' : '') . $args->link_after;

		// add support for menu item title
		if (strlen($item->attr_title)>2) {
			$item_output .= '<h3 class="tit">' . $item->attr_title . '</h3>';}
		// add support for menu item descriptions
		if (strlen($item->description)>2) {
			$item_output .= '</a> <span class="sub">' . $item->description . '</span>';}
		$item_output .= '</a>';
		$item_output .= $args->after;

		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
 
	function display_element( $element, &$children_elements, $max_depth, $depth=0, $args, &$output ) {
		//v($element);
		if ( !$element )
		return;
		
		$id_field = $this->db_fields['id'];
		
		//display this element
		if ( is_array( $args[0] ) )
		$args[0]['has_children'] = ! empty( $children_elements[$element->$id_field] );
		else if ( is_object( $args[0] ) )
		$args[0]->has_children = ! empty( $children_elements[$element->$id_field] );
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'start_el'), $cb_args);
		
		$id = $element->$id_field;
		
		// descend only when the depth is right and there are childrens for this element
		if ( ($max_depth == 0 || $max_depth > $depth+1 ) && isset( $children_elements[$id]) ) {
		
		foreach( $children_elements[ $id ] as $child ){
		
		if ( !isset($newlevel) ) {
		$newlevel = true;
		//start the child delimiter
		$cb_args = array_merge( array(&$output, $depth), $args);
		call_user_func_array(array(&$this, 'start_lvl'), $cb_args);
		}
		$this->display_element( $child, $children_elements, $max_depth, $depth + 1, $args, $output );
		}
		unset( $children_elements[ $id ] );
		}
		
		if ( isset($newlevel) && $newlevel ){
		//end the child delimiter
		$cb_args = array_merge( array(&$output, $depth), $args);
		call_user_func_array(array(&$this, 'end_lvl'), $cb_args);
		}
		
		//end this element
		$cb_args = array_merge( array(&$output, $element, $depth), $args);
		call_user_func_array(array(&$this, 'end_el'), $cb_args);
	}
}
