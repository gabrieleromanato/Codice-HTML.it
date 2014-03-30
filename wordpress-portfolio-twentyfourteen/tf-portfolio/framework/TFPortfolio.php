<?php

class TFPortfolio {
	public function __construct() {
		add_action( 'init', array( &$this, 'createCustomPostType' ) );
		add_action( 'wp_enqueue_scripts', array( &$this, 'insertScripts' ) );
		add_shortcode( 'tf-portfolio', array( &$this, 'portfolioShortcode' ) );
	}
	
	public function insertScripts() {
		$url = plugins_url();
		$assetsURL = $url . '/tf-portfolio/assets/';
		
		wp_enqueue_style( 'portfolio', $assetsURL . 'css/portfolio.css' );
		wp_enqueue_style( 'colorbox', $assetsURL . 'js/colorbox/colorbox.css' );
		wp_enqueue_script( 'colorbox', $assetsURL . 'js/colorbox/jquery.colorbox.min.js', array( 'jquery' ), '1.3.21', false );
		wp_enqueue_script( 'portfolio', $assetsURL . 'js/portfolio.js', array( 'jquery' ), '1.0', false );
	}
	
	public function portfolioShortcode() {
		$html = '<div id="portfolio-items">' . "\n";
		$loop = new WP_Query( array( 'post_type' => 'portfolio', 'posts_per_page' => -1 ) );
		
		while( $loop->have_posts() ) {
			$loop->the_post();
			$id = get_the_ID();
			$title = get_the_title();
			$excerpt = strip_tags( get_the_excerpt() );
			$thumb_id = get_post_thumbnail_id( $id );
			$image_full = wp_get_attachment_image_src( $thumb_id, 'full' );
			$image_thumb = wp_get_attachment_image_src( $thumb_id, 'large' );
			$permalink = get_permalink();
			
			$html .= '<div class="portfolio-item">' . "\n";
			$html .= '<a href="' . $image_full[0] . '" class="portfolio-lightbox" title="' . $title . '">' . "\n";
			$html .= '<img src="' . $image_thumb[0] . '" alt="" />' . "\n";
			$html .= '</a>' . "\n";
			$html .= '<h2 class="portfolio-title">' . $title . '</h2>' . "\n";
			$html .= '<p class="portfolio-excerpt">' . $excerpt . '</p>' . "\n";
			$html .= '<p class="portfolio-more"><a href="' . $permalink . '">' . __( 'Vedi progetto', 'tf-portfolio' ) . '</a></p>';
			$html .= '</div>'; 
		}
		
		$html .= '</div>';
		wp_reset_query();
		return $html;
	}
	
	public function createCustomPostType() {
  		$labels = array(
    		'name' => _x( 'Portfolio', 'post type general name' ),
    		'singular_name' => _x( 'Portfolio', 'post type singular name'),
    		'add_new' => _x( 'Add New', 'Portfolio' ),
    		'add_new_item' => __( 'Add New Portfolio' ),
    		'edit_item' => __( 'Edit Portfolio' ),
    		'new_item' => __( 'New Portfolio' ),
    		'all_items' => __( 'All Portfolio' ),
    		'view_item' => __( 'View Portfolio' ),
    		'search_items' => __( 'Search Portfolio' ),
    		'not_found' =>  __( 'No Portfolio found' ),
    		'not_found_in_trash' => __( 'No Portfolio found in Trash' ), 
    		'parent_item_colon' => '',
    		'menu_name' => __( 'Portfolio' )

  		);
  		$args = array(
    		'labels' => $labels,
    		'public' => true,
    		'publicly_queryable' => true,
    		'show_ui' => true, 
    		'show_in_menu' => true, 
    		'query_var' => true,
    		'rewrite' => array( 'slug'=> 'portfolio' ),
    		'capability_type' => 'post',
    		'has_archive' => true, 
    		'hierarchical' => false,
    		'menu_position' => 100,
    		'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt' )
  		);
  		
  		$catLabels = array(
			'name'              => _x( 'Portfolio Categories', 'taxonomy general name' ),
			'singular_name'     => _x( 'Portfolio Category', 'taxonomy singular name' ),
			'search_items'      => __( 'Search Portfolio Categories' ),
			'all_items'         => __( 'All Portfolio Categories' ),
			'parent_item'       => __( 'Parent Portfolio Category' ),
			'parent_item_colon' => __( 'Parent Portfolio Category:' ),
			'edit_item'         => __( 'Edit Portfolio Category' ),
			'update_item'       => __( 'Update Portfolio Category' ),
			'add_new_item'      => __( 'Add Portfolio Category' ),
			'new_item_name'     => __( 'New Portfolio Category' ),
			'menu_name'         => __( 'Portfolio Categories' ),
		);

		$catArgs = array(
			'hierarchical'      => true,
			'labels'            => $catLabels,
			'show_ui'           => true,
			'show_admin_column' => true,
			'query_var'         => true,
			'rewrite'           => array( 'slug' => 'portfolio-category' ),
		);
  		
  		register_post_type( 'portfolio', $args );
  		
  		
  		register_taxonomy( 'portfolio-category', array( 'portfolio' ), $catArgs );
  		
  		flush_rewrite_rules();
	}

}