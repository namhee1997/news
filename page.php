<?php get_header(); ?>

<main id="primary" role="main">
    <?php while ( have_posts() ) : the_post(); ?>

    <article id="post-<?php the_ID(); ?>" <?php post_class( 'page-wrap' ); ?>>
        <h1 class="page-title"><?php the_title(); ?></h1>

        <?php if ( has_post_thumbnail() ) : ?>
        <div class="single-post-thumb" style="margin-bottom:22px;">
            <?php the_post_thumbnail( 'news-wide', [ 'alt' => get_the_title() ] ); ?>
        </div>
        <?php endif; ?>

        <div class="page-content">
            <?php
            the_content();
            wp_link_pages( [
                'before' => '<div class="page-links">' . __( 'Pages:', 'news-1' ),
                'after'  => '</div>',
            ] );
            ?>
        </div>
    </article>

    <?php if ( comments_open() || get_comments_number() ) : ?>
        <?php comments_template(); ?>
    <?php endif; ?>

    <?php endwhile; ?>
</main>

<?php get_footer(); ?>
