<?php get_header(); ?>

<main id="primary" role="main">

    <div class="archive-header">
        <h1><?php printf( __( 'Search Results: &ldquo;%s&rdquo;', 'news-1' ), get_search_query() ); ?></h1>
        <?php global $wp_query; ?>
        <p><?php printf( __( 'Found %d result(s)', 'news-1' ), $wp_query->found_posts ); ?></p>
    </div>

    <!-- Search form -->
    <div class="search-form-wrap">
        <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
            <input type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>"
                   placeholder="<?php esc_attr_e( 'Search news...', 'news-1' ); ?>">
            <button type="submit">&#128269; <?php _e( 'Search', 'news-1' ); ?></button>
        </form>
    </div>

    <?php if ( have_posts() ) : ?>
    <div class="post-list">
        <?php while ( have_posts() ) : the_post(); ?>
        <article id="post-<?php the_ID(); ?>" class="post-list-item">
            <div class="post-list-thumb">
                <a href="<?php the_permalink(); ?>">
                    <?php echo news1_get_thumbnail( get_the_ID(), 'news-thumb', [ 'alt' => get_the_title() ] ); ?>
                </a>
            </div>
            <div class="post-list-body">
                <div class="post-list-cat"><?php echo strip_tags( news1_get_primary_cat() ); ?></div>
                <h2 class="post-list-title">
                    <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                </h2>
                <div style="font-size:14px;color:#666;margin:5px 0;"><?php the_excerpt(); ?></div>
                <div class="post-list-meta">
                    <?php the_author(); ?> &bull; <?php echo get_the_date(); ?>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <?php news1_pagination(); ?>

    <?php else : ?>
    <div style="background:#fff;padding:50px;text-align:center;border-radius:4px;box-shadow:0 1px 4px rgba(0,0,0,.08);">
        <p style="font-size:18px;color:#555;margin-bottom:12px;">
            <?php printf( __( 'No results found for &ldquo;%s&rdquo;', 'news-1' ), get_search_query() ); ?>
        </p>
        <p style="color:#888;"><?php _e( 'Try different or shorter keywords.', 'news-1' ); ?></p>
    </div>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
