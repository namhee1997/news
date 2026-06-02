<?php get_header(); ?>

<main id="primary" role="main">

    <div class="archive-header">
        <h1>
            <?php
            if ( is_search() ) {
                printf( __( 'Search Results: &ldquo;%s&rdquo;', 'news-1' ), get_search_query() );
            } else {
                the_archive_title();
            }
            ?>
        </h1>
        <?php if ( is_search() ) : global $wp_query; ?>
        <p><?php printf( __( 'Found %d results', 'news-1' ), $wp_query->found_posts ); ?></p>
        <?php else : the_archive_description( '<p>', '</p>' ); endif; ?>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="posts-grid">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" <?php post_class( 'post-card' ); ?>>
            <div class="post-card-thumb">
                <a href="<?php the_permalink(); ?>">
                    <?php echo news1_get_thumbnail( get_the_ID(), 'news-card', [ 'alt' => get_the_title() ] ); ?>
                </a>
            </div>
            <div class="post-card-body">
                <?php echo news1_get_primary_cat(); ?>
                <h2 class="post-card-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div class="post-card-meta">
                    <span><?php the_author(); ?></span>
                    <span><?php echo news1_time_ago(); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <?php news1_pagination(); ?>

    <?php else : ?>
    <div style="background:#fff;padding:40px;text-align:center;border-radius:4px;">
        <p style="color:#888;font-size:16px;"><?php _e( 'No posts found.', 'news-1' ); ?></p>
    </div>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
