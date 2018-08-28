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
     * ID passed in shortcode
     *
     * @since 1.0.5
     * @var int
     */
    private $id = 0;

    /**
     * class passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $class = '';

    /**
     * Type of post type passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $type = '';

    /**
     * count of items to be displayed passed in shortcode
     *
     * @since 1.0.5
     * @var int
     */
    private $items = 0;

    /**
     * Order of items to be displayed passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $order = '';

    /**
     * sorting of items to be displayed passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $order_by = '';

    /**
     * category passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $tax = '';

    /**
     * Term passed in shortcode
     *
     * @since 1.0.5
     * @var string
     */
    private $term = '';

    /**
     * Current Page number
     *
     * @since 1.0.5
     * @var int
     */
    private $paged = 0;

    /**
     * Pagination flag
     *
     * @since 1.0.5
     * @var int
     */
    private $pagination= '';

    /**
     * array of terms if term are passed as comma separated in shortcode
     *
     * @since 1.0.5
     * @var array
     */
    private $terms = array();

    /**
     * array of taxonomies if tax are passed as comma separated in shortcode
     *
     * @since 1.0.5
     * @var array
     */
    private $taxonomies = array();

    /**
     * array of post
     *
     * @since 1.0.5
     * @var array
     */
    private $isotope_loop = array();


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
     * @since    1.0.5
     * Moved shortcode argument extraction logic in a function
     * Moved filter logic in a function
     *
	 * @param    array $atts Shortcode attributes
	 */
	public function shortcode_isotope( $atts ) {

        $query_args = $this->extract_isotope( $atts );

        $this->isotope_loop = new WP_Query( $query_args );

        if ( $this->isotope_loop->have_posts() ) :
            ob_start(); ?>

            <ul id="filters-<?php echo esc_attr( $this->id ); ?>" class="filters">

                <li>
                    <a href="javascript:void(0)" title="filter all" data-filter=".all" class="active">
                        <?php esc_html_e( 'All', 'wanna-isotope' ); ?>
                    </a>
                </li>

            <?php

            $this->display_filter_isotope();

            ?>
            </ul>

            <ul id="<?php echo esc_attr( $this->id); ?>" class="isotope-content isotope">
            <?php
            while ( $this->isotope_loop->have_posts() ) : $this->isotope_loop->the_post();

                if ( null != $this->taxonomies ) {
                    $term_class = '';
                    foreach ( $this->taxonomies as $tax ) {//handling of multiple taxonomies
                        $tax_terms = get_the_terms( $this->isotope_loop->post->ID, $tax );
                        foreach ( (array) $tax_terms as $term ) {
                            $term_class .= $term->slug . ' ';
                        }
                    }
                }

                if ( file_exists( get_stylesheet_directory() . '/wanna-isotope/loop.php' ) ) {
        
                    // Load from child theme
                    load_template( get_stylesheet_directory() . '/wanna-isotope/loop.php', false );

                } elseif ( file_exists( get_template_directory() . '/wanna-isotope/loop.php' ) ) {
        
                    // Load from parent theme
                    load_template( get_template_directory() . '/wanna-isotope/loop.php', false );

                } else {
                    
                    // Load from plugin
                    include( plugin_dir_path(__FILE__) . 'templates/loop.php' );

                }

            endwhile; ?>
            </ul>

            <?php
            if ( 'yes' == $this->pagination ) {
                $this->pagination_isotope( $this->isotope_loop->max_num_pages );
            }
            ?>

            <script type="text/javascript">
                jQuery(document).ready(function($) {

                    var $container = $('#<?php echo esc_js( $this->id ); ?>');
                    $container.imagesLoaded( function(){
                        $container.isotope({
                          itemSelector: ".isotope-item",
                          layoutMode: "masonry"
                        });
                    });

                    var $optionSets = $('#filters-<?php echo esc_attr( $this->id ); ?>'),
                    $optionLinks = $optionSets.find('a');
                 
                    $optionLinks.click(function(){
                        var $this = $(this);
                        // don\'t proceed if already active
                        if ( $this.hasClass('active') ) {
                          return false;
                        }
                        var $optionSet = $this.parents('#filters-<?php echo esc_js( $this->id ); ?>');
                        $optionSets.find('.active').removeClass('active');
                        $this.addClass('active');
                     
                        //When an item is clicked, sort the items.
                        var selector = $(this).attr('data-filter');
                        $container.isotope({ filter: selector });

                        return false;
                    });
                });
            </script>

            <?php
            return ob_get_clean();

        endif;

        wp_reset_query();

	}



    /**
     * Extract attributes of the shortcode
     *
     * Implements the logic of extracting attributes passed in shortcode and setting them in class variables
     *
     * @since    1.0.5
     *
     *
     * @param    array $atts Shortcode attributes
     * @return   array $query_args query arguments to be used in WP Query
     */
    private function extract_isotope( $atts ) {

        extract( shortcode_atts( array(
            'id'         => '',
            'class'      => '',
            'type'       => 'post',
            'items'      => 4,
            'order'      => '',
            'order_by'   => 'menu_order',
            'tax'        => '',
            'term'       => '',
            'pagination' => 'no',
        ), $atts ) );

        $this->id = $id;
        $this->class = $class;
        $this->type = $type;
        $this->items = $items;
        $this->order = $order;
        $this->order_by = $order_by;
        $this->tax = $tax;
        $this->term = $term;
        $this->pagination = strtolower($pagination);
        $this->taxonomies = explode( ',',$this->tax );///this allows to have multiple comma separated taxonomies in shortcode

        if ( null == $this->id ) {
            $this->id = 'wanna' . md5( date( 'jnYgis' ) );
        }

        if ( 'yes' == $this->pagination ) {
            $this->paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : 1; //get current page number
        } else {
            $this->paged = null;
        }

        if ( null == $this->term ) {
            $query_args = array(
                'post_type'       => $this->type,
                'order'           => $this->order,
                'orderby'         => $this->order_by,
                'posts_per_page'  => $this->items,
                'paged'           => $this->paged,
                'meta_query'      => array(//get posts that have featured images in it
                    array(
                        'key'     => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ),
                )
            );
        } else {
            $tax_query_array = array();
            $tax_query_array['relation'] = 'OR';

            $temp_tax_query_array = array();

            $this->terms = explode( ',', $this->term );///this allows to have multiple comma separated terms in shortcode

            foreach ( $this->taxonomies as $tax ) {
                $temp_tax_query_array['taxonomy'] = $tax;
                $temp_tax_query_array['field'] = 'slug';
                $temp_tax_query_array['terms'] = $this->terms;
                $tax_query_array[] = $temp_tax_query_array;
            }

            $query_args = array(
                'post_type'       => $this->type,
                'order'           => $this->order,
                'orderby'         => $this->order_by,
                'posts_per_page'  => $this->items,
                'paged'           => $this->paged,
                'tax_query'       => $tax_query_array,
                'meta_query'      => array( //get posts that have featured images attached with them only
                    array(
                        'key'     => '_thumbnail_id',
                        'compare' => 'EXISTS'
                    ),
                ),
            );
        }
        return $query_args;
    }

    /**
     * Pagination Logic
     *
     * Implements the logic of number pagination
     *
     * @since    1.0.5
     *
     *
     * @param    int $pages amount of pages returned by WP_Query
     * @param    int $range range of pages on pagination strip
     */
    private function pagination_isotope( $pages = '', $range = 4 ) {
        $showitems = ( $range * 2 ) + 1;


        global $paged;
        if ( empty( $paged ) ) {
            $paged = 1;
        }

        if ( $pages == '' ) {
            global $wp_query;
            $pages = $wp_query->max_num_pages;
            if ( !$pages ) {
                $pages = 1;
            }
        }

        if ( 1 != $pages ) {

            echo "<div class=\"pagination prefix\"><span>";
            printf( esc_html__( 'Page %d of %d.', 'wanna-isotope' ), $paged, $pages );
            echo "</span>";

            if ( $paged > 2 && $paged > $range + 1 && $showitems < $pages ) {
                echo "<a href='".get_pagenum_link( 1 )."'>&laquo; ";
                esc_html_e( 'First', 'wanna-isotope' );
                echo "</a>";
            }

            if ( $paged > 1 && $showitems < $pages ) {
                echo "<a href='" . get_pagenum_link( $paged - 1 ) . "'>&lsaquo; ";
                esc_html_e( 'Previous', 'wanna-isotope' );
                echo"</a>";
            }

            for ( $i=1; $i <= $pages; $i++ ) {
                if ( 1 != $pages && ( !( $i >= $paged + $range + 1 || $i <= $paged - $range - 1 ) || $pages <= $showitems ) ) {
                    echo ( $paged == $i )? "<span class=\"current\">".$i."</span>":"<a href='".get_pagenum_link( $i )."' class=\"inactive\">".$i."</a>";
                }
            }

            if ( $paged < $pages && $showitems < $pages ) {
                echo "<a href=\"". get_pagenum_link( $paged + 1 ) ."\">";
                esc_html_e( 'Next', 'wanna-isotope' );
                echo" &rsaquo;</a>";
            }

            if ( $paged < $pages - 1 &&  $paged + $range - 1 < $pages && $showitems < $pages ) {
                echo "<a href='".get_pagenum_link( $pages )."'>";
                esc_html_e( 'Last', 'wanna-isotope' );
                echo " &raquo;</a>";
            }

            echo "</div>\n";
        }
    }

    /**
     * Display filter
     *
     * Implements the logic of displaying term filter above the content
     * Terms associated with posts are pulled and displayed as filters. The enhanced logic helps in pagination
     * as terms associated with posts ,being displayed, are shown as filters
     *
     * @since    1.0.5
     *
     *
     */
    private function display_filter_isotope() {

        $term_array_2 = array();

        while ( $this->isotope_loop->have_posts() ) : $this->isotope_loop->the_post();
            if ( null != $this->taxonomies ) {
                foreach ( $this->taxonomies as $tax ) {//handling of multiple taxonomies
                    $tax_terms = get_the_terms( $this->isotope_loop->post->ID, $tax ); //get all terms associated with post

                    if ( $tax_terms != null && $this->terms == null ) {// when there are no terms in shortcodes then show all terms associated with posts
                        foreach ( (array)$tax_terms as $term ) {
                            $termslug = strtolower( $term->slug );
                            $termname = strtolower( $term->name );
                            $term_array_2[$termslug] = $termname;
                        }
                    } elseif ( $tax_terms != null && $this->terms != null ) {// when there are terms in shortcodes then show terms mentioned in shortcode AND associated with posts
                        foreach ( (array)$tax_terms as $term ) {
                            $termslug = strtolower( $term->slug );
                            $termname = strtolower( $term->name );
                            $term_children = get_term_children( $term->term_id, $tax );
                            if ( in_array( $termslug, $this->terms ) ) { //logic to display terms mentioned in short code
                                $term_array_2[$termslug] = $termname;
                            }
                            $count = count( $term_children );
                            if ( $count > 0 ) { //logic to display child terms if parent term is mentioned in the short code
                                foreach ( $term_children as $term_child ) {
                                    $single_term = get_term( $term_child, $tax );
                                    $termslug = strtolower( $single_term->slug );
                                    $termname = strtolower( $single_term->name );
                                    $term_array_2[$termslug] = $termname;
                                }
                            }
                        }
                    }
                }
            }
        endwhile;

        if ( count( $term_array_2 ) > 1 ) { //if there is one term then display "All" rather than displaying "All" and name of term itself
            foreach ( $term_array_2 as $termslug => $termname ) {?>
                <li>
                <a href="javascript:void(0)" title="filter <?php echo esc_attr( $termname ); ?>"
                   data-filter=".<?php echo esc_attr( $termslug ); ?>">
                    <?php echo esc_html( $termname ); ?>
                </a>
                </li><?php
            }
        }

    }
}