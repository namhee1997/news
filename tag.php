<?php get_header(); ?>

<main id="primary" role="main">

    <?php
    $tag        = get_queried_object();
    $sort_param = isset( $_GET['sort'] ) && $_GET['sort'] === 'popular' ? 'popular' : 'newest';
    $paged      = max( 1, get_query_var( 'paged' ) );
    $base_url   = get_term_link( $tag );

    $query_args = [
        'tag__in'             => [ $tag->term_id ],
        'posts_per_page'      => 12,
        'paged'               => $paged,
        'ignore_sticky_posts' => true,
    ];

    if ( $sort_param === 'popular' ) {
        $query_args['meta_key'] = '_post_views_count';
        $query_args['orderby']  = 'meta_value_num';
        $query_args['order']    = 'DESC';
    } else {
        $query_args['orderby'] = 'date';
        $query_args['order']   = 'DESC';
    }

    $tag_query = new WP_Query( $query_args );
    ?>

    <div class="archive-header">
        <h1><?php printf( __( 'Tag: %s', 'news-1' ), esc_html( $tag->name ) ); ?></h1>
        <?php if ( $tag->description ) : ?>
        <p><?php echo esc_html( $tag->description ); ?></p>
        <?php endif; ?>
    </div>

    <!-- Sort controls -->
    <div class="tag-sort-bar">
        <span class="sort-label"><?php _e( 'Sort by:', 'news-1' ); ?></span>
        <a href="<?php echo esc_url( add_query_arg( 'sort', 'newest', $base_url ) ); ?>"
           class="<?php echo $sort_param === 'newest' ? 'active' : ''; ?>">
            <?php _e( 'Newest', 'news-1' ); ?>
        </a>
        <a href="<?php echo esc_url( add_query_arg( 'sort', 'popular', $base_url ) ); ?>"
           class="<?php echo $sort_param === 'popular' ? 'active' : ''; ?>">
            <?php _e( 'Most Viewed', 'news-1' ); ?>
        </a>
    </div>

    <?php if ( $tag_query->have_posts() ) : ?>
    <div class="posts-grid">
        <?php while ( $tag_query->have_posts() ) : $tag_query->the_post(); ?>
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
                    <span><?php echo get_the_date(); ?></span>
                    <?php if ( $sort_param === 'popular' ) : ?>
                    <span><?php echo number_format( news1_get_views() ); ?> <?php _e( 'views', 'news-1' ); ?></span>
                    <?php endif; ?>
                </div>
            </div>
        </article>
        <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php news1_pagination( $tag_query ); ?>

    <?php else : ?>
    <div style="background:#fff;padding:40px;text-align:center;border-radius:4px;">
        <p style="color:#888;font-size:16px;"><?php _e( 'No posts found with this tag.', 'news-1' ); ?></p>
    </div>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
