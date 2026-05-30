<?php get_header(); ?>

<main id="primary" role="main">

    <?php $cat = get_queried_object(); ?>
    <div class="archive-header">
        <h1><?php echo esc_html( $cat->name ); ?></h1>
        <?php if ( $cat->description ) : ?>
        <p><?php echo esc_html( $cat->description ); ?></p>
        <?php else : ?>
        <p><?php printf( __( 'All articles in category: %s', 'news-1' ), esc_html( $cat->name ) ); ?></p>
        <?php endif; ?>
    </div>

    <!-- Sub-categories -->
    <?php
    $sub_cats = get_categories( [ 'parent' => $cat->term_id, 'hide_empty' => false ] );
    if ( $sub_cats ) :
    ?>
    <div style="margin-bottom:20px;display:flex;flex-wrap:wrap;gap:8px;">
        <?php foreach ( $sub_cats as $sc ) : ?>
        <a href="<?php echo esc_url( get_category_link( $sc->term_id ) ); ?>"
           style="background:#fff;border:1px solid var(--color-border);padding:6px 14px;border-radius:20px;font-size:13px;font-weight:600;color:#555;transition:all .2s;"
           onmouseover="this.style.background='var(--color-primary)';this.style.color='#fff';this.style.borderColor='var(--color-primary)'"
           onmouseout="this.style.background='#fff';this.style.color='#555';this.style.borderColor='var(--color-border)'">
            <?php echo esc_html( $sc->name ); ?>
            <span style="color:#aaa;margin-left:4px;">(<?php echo $sc->count; ?>)</span>
        </a>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

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
                    <span><?php echo get_the_date(); ?></span>
                    <span><?php comments_number( '0', '1', '%' ); ?> <?php _e( 'cmts', 'news-1' ); ?></span>
                </div>
            </div>
        </article>
        <?php endwhile; ?>
    </div>

    <?php news1_pagination(); ?>

    <?php else : ?>
    <div style="background:#fff;padding:40px;text-align:center;border-radius:4px;">
        <p style="color:#888;font-size:16px;"><?php _e( 'No posts found in this category.', 'news-1' ); ?></p>
    </div>
    <?php endif; ?>

</main>

<?php get_footer(); ?>
