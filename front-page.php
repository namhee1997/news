<?php get_header(); ?>

<main id="primary" role="main">

    <!-- ===== HERO (top 3 posts by views today, fill remainder with latest) ===== -->
    <?php
    $today_key  = news1_today_view_key();

    // Step 1: IDs of posts viewed today, sorted by view count desc
    $viewed_q   = new WP_Query( [
        'posts_per_page'      => 3,
        'fields'              => 'ids',
        'meta_key'            => $today_key,
        'orderby'             => 'meta_value_num',
        'order'               => 'DESC',
        'ignore_sticky_posts' => false,
        'meta_query'          => [ [ 'key' => $today_key, 'compare' => 'EXISTS' ] ],
    ] );
    $hero_ids = (array) $viewed_q->posts;
    $found    = count( $hero_ids );

    // Step 2: fill remaining slots with latest posts
    if ( $found < 3 ) {
        $exclude  = $found > 0 ? $hero_ids : [ 0 ];
        $fill_q   = new WP_Query( [
            'posts_per_page'      => 3 - $found,
            'fields'              => 'ids',
            'orderby'             => 'date',
            'order'               => 'DESC',
            'ignore_sticky_posts' => false,
            'post__not_in'        => $exclude,
        ] );
        $hero_ids = array_merge( $hero_ids, (array) $fill_q->posts );
    }

    // Step 3: single clean query preserving order
    $hero_q = empty( $hero_ids )
        ? new WP_Query( [ 'posts_per_page' => 3 ] )
        : new WP_Query( [
            'post__in'            => $hero_ids,
            'posts_per_page'      => 3,
            'orderby'             => 'post__in',
            'ignore_sticky_posts' => false,
        ] );

    if ( $hero_q->have_posts() ) :
    ?>
    <div class="hero-grid">
        <?php $hero_q->the_post(); ?>
        <div class="hero-main">
            <a href="<?php the_permalink(); ?>">
                <?php echo news1_get_thumbnail( get_the_ID(), 'news-hero', [ 'alt' => get_the_title() ] ); ?>
            </a>
            <div class="hero-overlay">
                <span class="hero-cat"><?php echo strip_tags( news1_get_primary_cat() ); ?></span>
                <div class="hero-title">
                    <a href="<?php the_permalink(); ?>" style="color:#fff;"><?php the_title(); ?></a>
                </div>
                <div class="hero-meta"><?php the_author(); ?> &bull; <?php echo news1_time_ago(); ?></div>
            </div>
        </div>
        <?php while ( $hero_q->have_posts() ) : $hero_q->the_post(); ?>
        <div class="hero-side">
            <a href="<?php the_permalink(); ?>">
                <?php echo news1_get_thumbnail( get_the_ID(), 'news-card', [ 'alt' => get_the_title() ] ); ?>
            </a>
            <div class="hero-overlay">
                <span class="hero-cat"><?php echo strip_tags( news1_get_primary_cat() ); ?></span>
                <div class="hero-title">
                    <a href="<?php the_permalink(); ?>" style="color:#fff;"><?php the_title(); ?></a>
                </div>
                <div class="hero-meta"><?php echo news1_time_ago(); ?></div>
            </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <!-- ===== LATEST NEWS ===== -->
    <h2 class="section-title"><?php _e( 'Latest News', 'news-1' ); ?></h2>
    <?php
    $latest = new WP_Query( [ 'posts_per_page' => 6, 'ignore_sticky_posts' => true ] );
    if ( $latest->have_posts() ) :
    ?>
    <div class="posts-grid" style="margin-bottom:32px;">
        <?php while ( $latest->have_posts() ) : $latest->the_post(); ?>
        <?php get_template_part( 'template-parts/card', 'post' ); ?>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif; ?>

    <!-- ===== CATEGORY SECTIONS (top 4 most populated cats) ===== -->
    <?php
    $feature_cats = get_categories( [ 'number' => 20, 'hide_empty' => true, 'orderby' => 'count', 'order' => 'DESC' ] );
    foreach ( $feature_cats as $fc ) :
        $cat_q = new WP_Query( [ 'cat' => $fc->term_id, 'posts_per_page' => 4, 'ignore_sticky_posts' => true ] );
        if ( ! $cat_q->have_posts() ) continue;
    ?>
    <div class="cat-section">
        <div class="cat-section-header">
            <h2 class="section-title" style="margin-bottom:0;"><?php echo esc_html( $fc->name ); ?></h2>
            <a href="<?php echo esc_url( get_category_link( $fc->term_id ) ); ?>" class="cat-see-all">
                <?php _e( 'See all &raquo;', 'news-1' ); ?>
            </a>
        </div>
        <div class="cat-section-grid">
            <!-- Featured post -->
            <?php $cat_q->the_post(); ?>
            <div class="cat-featured-post">
                <a href="<?php the_permalink(); ?>">
                    <?php echo news1_get_thumbnail( get_the_ID(), 'news-hero', [ 'alt' => get_the_title() ] ); ?>
                </a>
                <div class="hero-overlay">
                    <div class="hero-title" style="font-size:18px;">
                        <a href="<?php the_permalink(); ?>" style="color:#fff;"><?php the_title(); ?></a>
                    </div>
                    <div class="hero-meta"><?php the_author(); ?> &bull; <?php echo news1_time_ago(); ?></div>
                </div>
            </div>
            <!-- Side list -->
            <div class="post-list">
                <?php while ( $cat_q->have_posts() ) : $cat_q->the_post(); ?>
                <div class="post-list-item">
                    <div class="post-list-thumb">
                        <a href="<?php the_permalink(); ?>">
                            <?php echo news1_get_thumbnail( get_the_ID(), 'news-thumb', [ 'alt' => get_the_title() ] ); ?>
                        </a>
                    </div>
                    <div class="post-list-body">
                        <div class="post-list-title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></div>
                        <div class="post-list-meta"><?php echo news1_time_ago(); ?></div>
                    </div>
                </div>
                <?php endwhile; wp_reset_postdata(); ?>
            </div>
        </div>
    </div>
    <?php endforeach; ?>

</main>

<?php get_footer(); ?>