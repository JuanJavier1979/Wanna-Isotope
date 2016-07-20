<li class="isotope-item <?php echo esc_attr( $term_class ); ?> all">
<?php if( has_post_thumbnail( $isotope_loop->ID ) ) : ?>
    <a href="<?php the_permalink(); ?>" title="<?php the_title(); ?>">
        <?php the_post_thumbnail( $isotope_loop->ID, 'medium' ); ?>
    </a>
<?php endif; ?>
</li>
