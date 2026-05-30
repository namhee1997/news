    </div><!-- .container -->
</div><!-- #main-content -->

<!-- ===== FOOTER ===== -->
<footer id="site-footer">

    <div class="footer-top-bar">
        <div class="container">
            <div class="footer-brand">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="footer-site-name">
                    <?php bloginfo( 'name' ); ?>
                </a>
                <p class="footer-tagline"><?php bloginfo( 'description' ); ?></p>
            </div>
            <div class="footer-social-icons">
                <?php
                $fb  = news1_get_social( 'news1_facebook_url' );
                $tw  = news1_get_social( 'news1_twitter_url' );
                $red = news1_get_social( 'news1_reddit_url' );
                ?>
                <?php if ( $fb ) : ?>
                <a href="<?php echo esc_url( $fb ); ?>" class="footer-social-btn footer-social-fb"
                   target="_blank" rel="noopener" aria-label="Facebook">
                    <span class="social-icon">f</span> Facebook
                </a>
                <?php endif; ?>
                <?php if ( $tw ) : ?>
                <a href="<?php echo esc_url( $tw ); ?>" class="footer-social-btn footer-social-x"
                   target="_blank" rel="noopener" aria-label="X Twitter">
                    <span class="social-icon">&#120143;</span> X
                </a>
                <?php endif; ?>
                <?php if ( $red ) : ?>
                <a href="<?php echo esc_url( $red ); ?>" class="footer-social-btn footer-social-reddit"
                   target="_blank" rel="noopener" aria-label="Reddit">
                    <span class="social-icon">&#128125;</span> Reddit
                </a>
                <?php endif; ?>
                <?php if ( ! $fb && ! $tw && ! $red ) : ?>
                <span style="color:#888;font-size:13px;">
                    <?php _e( 'Set social links in Appearance &rarr; Customize &rarr; Social Media Links', 'news-1' ); ?>
                </span>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="footer-widgets">
        <div class="container">
            <div class="footer-widgets-grid">

                <!-- Col 1: About -->
                <div class="footer-widget">
                    <?php if ( is_active_sidebar( 'footer-1' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-1' ); ?>
                    <?php else : ?>
                        <h3 class="footer-widget-title"><?php _e( 'About Us', 'news-1' ); ?></h3>
                        <p><?php _e( 'Bringing you the latest news and updates from around the world, 24/7.', 'news-1' ); ?></p>
                        <p style="margin-top:10px;font-size:13px;color:#888;">
                            <?php _e( 'Fast. Accurate. Trusted.', 'news-1' ); ?>
                        </p>
                    <?php endif; ?>
                </div>

                <!-- Col 2: Categories -->
                <div class="footer-widget">
                    <?php if ( is_active_sidebar( 'footer-2' ) ) : ?>
                        <?php dynamic_sidebar( 'footer-2' ); ?>
                    <?php else : ?>
                        <h3 class="footer-widget-title"><?php _e( 'Categories', 'news-1' ); ?></h3>
                        <ul>
                            <?php
                            $cats = get_categories( [ 'number' => 8, 'hide_empty' => false ] );
                            foreach ( $cats as $cat ) :
                            ?>
                            <li>
                                <a href="<?php echo esc_url( get_category_link( $cat->term_id ) ); ?>">
                                    <?php echo esc_html( $cat->name ); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

                <!-- Col 3: Footer Menu / Quick Links -->
                <div class="footer-widget">
                    <h3 class="footer-widget-title"><?php _e( 'Quick Links', 'news-1' ); ?></h3>
                    <?php if ( has_nav_menu( 'footer' ) ) : ?>
                        <?php wp_nav_menu( [
                            'theme_location' => 'footer',
                            'container'      => false,
                            'depth'          => 1,
                            'fallback_cb'    => false,
                        ] ); ?>
                    <?php else : ?>
                        <ul>
                            <li><a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php _e( 'Home', 'news-1' ); ?></a></li>
                            <?php
                            $pages = get_pages( [ 'number' => 6 ] );
                            foreach ( $pages as $p ) :
                            ?>
                            <li>
                                <a href="<?php echo esc_url( get_permalink( $p->ID ) ); ?>">
                                    <?php echo esc_html( $p->post_title ); ?>
                                </a>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <!-- Footer Bottom -->
    <div class="footer-bottom">
        <div class="container">
            <div class="footer-bottom-inner">
                <p>
                    &copy; <?php echo date( 'Y' ); ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php bloginfo( 'name' ); ?></a>.
                    <?php _e( 'All rights reserved.', 'news-1' ); ?>
                </p>
                <p class="footer-legal-links">
                    <?php
                    $legal = [];
                    $pages = get_pages();
                    foreach ( $pages as $p ) {
                        $slug = $p->post_name;
                        if ( in_array( $slug, [ 'privacy-policy', 'terms-of-use', 'contact-us', 'contact' ] ) ) {
                            $legal[] = '<a href="' . esc_url( get_permalink( $p->ID ) ) . '">' . esc_html( $p->post_title ) . '</a>';
                        }
                    }
                    echo implode( ' &middot; ', $legal );
                    ?>
                </p>
            </div>
        </div>
    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>
