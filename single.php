<?php get_header(); ?>

<div class="single-content-wrap">

    <!-- ===== ARTICLE + COMMENTS ===== -->
    <main id="primary" class="single-main" role="main">
        <?php while ( have_posts() ) : the_post(); ?>

        <article id="post-<?php the_ID(); ?>" <?php post_class( 'single-post-wrap' ); ?>>

            <!-- Categories -->
            <div class="single-post-cats">
                <?php
                $cats = get_the_category();
                foreach ( $cats as $cat ) :
                ?>
                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                <?php endforeach; ?>
            </div>

            <!-- Title -->
            <h1 class="single-post-title"><?php the_title(); ?></h1>

            <!-- Meta -->
            <div class="single-post-meta">
                <?php
                $author_bio = get_the_author_meta( 'description' );
                if ( ! $author_bio ) {
                    $author_bio = sprintf(
                        __( '%s is a sports and events writer for ClutchPoints, primarily covering events and athletes from the NBA, NFL, NCAA Basketball, and MLB. The Denver native graduated from Colorado State and has previously written at three local news outlets in the area.', 'news-1' ),
                        get_the_author()
                    );
                }
                ?>
                <span class="author-trigger-wrap">
                    <?php _e( 'By', 'news-1' ); ?>
                    <button type="button" class="author-popup-trigger">
                        <?php the_author(); ?>
                    </button>
                    <div class="author-tooltip" role="tooltip">
                        <strong class="author-tooltip-name"><?php echo esc_html( get_the_author() ); ?></strong>
                        <p class="author-tooltip-bio"><?php echo esc_html( $author_bio ); ?></p>
                    </div>
                </span>
                <span><?php echo news1_time_ago(); ?></span>
                <?php
                $pub_ts = get_post_time( 'U' );
                $mod_ts = get_post_modified_time( 'U' );
                if ( $mod_ts > $pub_ts ) :
                ?>
                <span><?php _e( 'Updated:', 'news-1' ); ?> <?php echo news1_time_ago( $mod_ts ); ?></span>
                <?php endif; ?>
                <?php if ( comments_open() ) : ?>
                <span>
                    <a href="#comments">
                        <?php comments_number( __( 'No comments', 'news-1' ), __( '1 comment', 'news-1' ), __( '% comments', 'news-1' ) ); ?>
                    </a>
                </span>
                <?php endif; ?>
                <?php
                $words   = str_word_count( strip_tags( get_the_content() ) );
                $est_min = max( 1, round( $words / 200 ) );
                ?>
                <span><?php echo $est_min; ?> <?php _e( 'min read', 'news-1' ); ?></span>
                <span><?php echo number_format( news1_get_views() ); ?> <?php _e( 'views', 'news-1' ); ?></span>
            </div>

            <!-- Share (top) -->
            <div class="post-share">
                <span class="post-share-label"><?php _e( 'Share:', 'news-1' ); ?></span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                   class="share-btn facebook" target="_blank" rel="noopener noreferrer">
                    f
                </a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn twitter" target="_blank" rel="noopener noreferrer">
                    &#120143;
                </a>
                <button class="share-btn copy" data-url="<?php the_permalink(); ?>">
                    &#128279; Copy Link
                </button>
            </div>

            <!-- Featured Image -->
            <?php if ( has_post_thumbnail() ) : ?>
            <div class="single-post-thumb">
                <?php the_post_thumbnail( 'full', [ 'alt' => get_the_title() ] ); ?>
                <?php $cap = get_the_post_thumbnail_caption(); if ( $cap ) : ?>
                <p class="thumb-caption"><?php echo esc_html( $cap ); ?></p>
                <?php endif; ?>
            </div>
            <?php else : ?>
            <div class="single-post-thumb">
                <?php echo news1_get_thumbnail( get_the_ID(), 'news-wide', [ 'alt' => get_the_title() ] ); ?>
            </div>
            <?php endif; ?>

            <!-- Content -->
            <div class="single-post-content">
                <?php
                the_content();
                wp_link_pages( [
                    'before' => '<div class="page-links">' . __( 'Pages:', 'news-1' ),
                    'after'  => '</div>',
                ] );
                ?>
            </div>

            <!-- Tags -->
            <?php $tags = get_the_tags(); if ( $tags ) : ?>
            <div class="post-tags">
                <span class="post-tags-label"><?php _e( 'Tags:', 'news-1' ); ?></span>
                <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>"><?php echo esc_html( $tag->name ); ?></a>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>

            <!-- Share (bottom) -->
            <div class="post-share" style="margin-top:20px;">
                <span class="post-share-label"><?php _e( 'Share this article:', 'news-1' ); ?></span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>"
                   class="share-btn facebook" target="_blank" rel="noopener noreferrer">f</a>
                <a href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>"
                   class="share-btn twitter" target="_blank" rel="noopener noreferrer">&#120143;</a>
                
            </div>

        </article>

        <!-- Prev / Next post navigation -->
        <?php
        $prev = get_previous_post();
        $next = get_next_post();
        if ( $prev || $next ) :
        ?>
        <div class="post-nav-grid">
            <?php if ( $prev ) : ?>
            <a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>" class="post-nav-item">
                <div class="post-nav-label">&laquo; <?php _e( 'Previous', 'news-1' ); ?></div>
                <div class="post-nav-title"><?php echo esc_html( get_the_title( $prev->ID ) ); ?></div>
            </a>
            <?php else : ?>
            <div></div>
            <?php endif; ?>
            <?php if ( $next ) : ?>
            <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>" class="post-nav-item" style="text-align:right;">
                <div class="post-nav-label"><?php _e( 'Next', 'news-1' ); ?> &raquo;</div>
                <div class="post-nav-title"><?php echo esc_html( get_the_title( $next->ID ) ); ?></div>
            </a>
            <?php endif; ?>
        </div>
        <?php endif; ?>

        <!-- Comments -->
        <?php if ( comments_open() || get_comments_number() ) : ?>
            <?php comments_template(); ?>
        <?php endif; ?>

        <?php endwhile; ?>
    </main>

    <!-- ===== STICKY RELATED ARTICLES SIDEBAR ===== -->
    <aside class="single-related-sidebar" role="complementary">
        <?php news1_related_posts( get_the_ID(), 8 ); ?>
    </aside>

</div>

<?php get_footer(); ?>