<?php
/**
 * The [isotope] shortcode of the plugin.
 *
 * @link       http://www.wannathemes.com
 * @since      1.0.0
 *
 * @package    Wanna_Isotope
 * @subpackage Wanna_Isotope/public
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Wanna_Isotope Shortcode Class
 *
 * @package Wanna_Isotope_Shortcode
 * @author  Juan Javier Moreno <hello@wannathemes.com>
 *
 * @since 1.0.0
 */
class Wanna_Isotope_Shortcode {

	/**
	 * Add shortcode
	 *
	 * @since    1.0.0
	 */
	public function __construct() {

		// Register shortcode
		add_shortcode( 'isotope', array( $this, 'shortcode_isotope' ) );

	}

	/**
	 * Isotope output
	 *
	 * Retrieves a media files and settings to display a video.
	 *
	 * @since    1.0.0
	 *
	 * @param    array $atts Shortcode attributes
	 */
	public function shortcode_isotope( $atts ) {

        extract( shortcode_atts( array(
            'id'        => '',
            'class'     => '',
            'type'      => 'post',
            'items'     => 4,
            'order'     => '',
            'order_by'  => 'menu_order',
            'tax'       => '',
            'term'      => '',
        ), $atts) );

        if( null != $id ) {
            $id_output = 'id="' . $id . '"';
        }

        if( null == $id ) {
	        $id = 'wanna'.md5( date( 'jnYgis' ) );
	        $id_output = 'id="' . $id . '"';
	    }

        if( $term == null ) {
            $isotope_loop = new WP_Query ( array(
                'post_type'       => $type,
                'order'           => $order,
                'orderby'         => $order_by,
                'posts_per_page'  => $items
            )  );
        } else {
            $isotope_loop = new WP_Query ( array(
                'post_type'       => $type,
                'order'           => $order,
                'orderby'         => $order_by,
                'posts_per_page'  => $items,
                'tax_query' => array(
                    array(
                        'taxonomy' => $tax,
                        'field'    => 'slug',
                        'terms'    => $term,
                    ),
                ),
            )  );
        }

        $isotope_output = '';
        
        if ( $isotope_loop->have_posts() ) :

            if( $tax != null && $term == null ) {
                $isotope_output .= '<ul id="filters-' . $id . '" class="filters">';
                $terms = get_terms( $tax );
                $count = count($terms);
                $isotope_output .= '<li><a href="javascript:void(0)" title="filter all" data-filter=".all" class="active">All</a></li>';
                if ( $count > 0 ){
                    foreach ( $terms as $term ) {
                        $termname = strtolower($term->slug);
                        $isotope_output .= '<li><a href="javascript:void(0)" title="filter ' . $term->name . '" data-filter=".' . $termname . '">' . $term->name . '</a></li>';
                    }
                }
                $isotope_output .= '</ul>';
            } elseif ( $term != null ) {
                $isotope_output .= '<ul id="filters-' . $id . '" class="filters">';
                $term_id = get_term_by( 'slug', $term, $tax );
                $terms = get_term_children( $term_id->term_id, $tax );
                $count = count($terms);
                $isotope_output .= '<li><a href="javascript:void(0)" title="filter all" data-filter=".all" class="active">All</a></li>';
                if ( $count > 0 ){
                    foreach ( $terms as $term ) {
                        $single_term = get_term( $term, $tax );
                        $termslug = strtolower($single_term->slug);
                        $termname = strtolower($single_term->name);
                        $isotope_output .= '<li><a href="javascript:void(0)" title="filter ' . $termslug . '" data-filter=".' . $termslug . '">' . $termname . '</a></li>';
                    }
                }
                $isotope_output .= '</ul>';
            }

            $isotope_output .= '<ul ' . $id_output . ' class="isotope-content isotope">';  

            while ( $isotope_loop->have_posts() ) : $isotope_loop->the_post();
                if( has_post_thumbnail( $isotope_loop->ID ) ) {   
                    $image = '<a href="' . get_the_permalink() . '" title="' . get_the_title() . '">' . get_the_post_thumbnail( $isotope_loop->ID, 'medium' ) . '</a>';
                }
                if( $tax != null ) {
                    $tax_terms = get_the_terms( $isotope_loop->ID, $tax );
                    $term_class = '';
                    foreach( (array)$tax_terms as $term ) {
                        $term_class .= $term->slug . ' '; 
                    }
                }
                $isotope_output .= '<li class="isotope-item ' . $term_class . 'all">' . $image . '</li>';
                $image = '';
            endwhile;

            $isotope_output .= '</ul>';

        endif;

        wp_reset_query();

        $isotope_output .= '
        <script type="text/javascript">
            jQuery(document).ready(function($) {
                var $container = $(\'#' . $id . '\');
                $container.imagesLoaded( function(){
                    $container.isotope({
                      itemSelector: ".isotope-item",
                      layoutMode: "masonry"
                    });
                });

                var $optionSets = $(\'#filters-' . $id . '\'),
                $optionLinks = $optionSets.find(\'a\');
             
                $optionLinks.click(function(){
                    var $this = $(this);
                    // don\'t proceed if already active
                    if ( $this.hasClass(\'active\') ) {
                      return false;
                    }
                    var $optionSet = $this.parents(\'#filters-' . $id . '\');
                    $optionSets.find(\'.active\').removeClass(\'active\');
                    $this.addClass(\'active\');
                 
                    //When an item is clicked, sort the items.
                     var selector = $(this).attr(\'data-filter\');
                    $container.isotope({ filter: selector });

                    return false;
                });
            }); 
        </script>';

        return $isotope_output;

	}

}