<?php get_header(); ?>

<main id="primary" role="main">
    <div class="error-404-wrap">
        <div class="code">404</div>
        <h2><?php _e( 'Page Not Found', 'news-1' ); ?></h2>
        <p><?php _e( 'The page you are looking for might have been removed, renamed, or is temporarily unavailable.', 'news-1' ); ?></p>

        <form role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>"
              style="max-width:400px;margin:0 auto 24px;display:flex;border:1px solid #ddd;border-radius:4px;overflow:hidden;">
            <input type="search" name="s"
                   placeholder="<?php esc_attr_e( 'Search for content...', 'news-1' ); ?>"
                   style="flex:1;border:none;padding:11px 14px;font-size:14px;outline:none;">
            <button type="submit"
                    style="background:var(--color-primary);color:#fff;border:none;padding:0 18px;cursor:pointer;font-size:16px;"
                    aria-label="Search">&#128269;</button>
        </form>

        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="btn-primary">
            &larr; <?php _e( 'Back to Home', 'news-1' ); ?>
        </a>
    </div>
</main>

<?php get_footer(); ?>
