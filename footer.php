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
                    <span class="social-icon">f</span>
                </a>
                <?php endif; ?>
                <?php if ( $tw ) : ?>
                <a href="<?php echo esc_url( $tw ); ?>" class="footer-social-btn footer-social-x"
                   target="_blank" rel="noopener" aria-label="X Twitter">
                    <span class="social-icon">&#120143;</span>
                </a>
                <?php endif; ?>
                <?php if ( $red ) : ?>
                <a href="<?php echo esc_url( $red ); ?>" class="footer-social-btn footer-social-reddit"
                   target="_blank" rel="noopener" aria-label="Reddit"> <?xml version="1.0" encoding="utf-8"?>
<!-- License: MIT. Made by Garuda Technology: https://github.com/garudatechnologydevelopers/sketch-icons -->
<svg width="25px" height="25px" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M16 2C8.27812 2 2 8.27812 2 16C2 23.7219 8.27812 30 16 30C23.7219 30 30 23.7219 30 16C30 8.27812 23.7219 2 16 2Z" fill="#FC471E"/>
<path fill-rule="evenodd" clip-rule="evenodd" d="M20.0193 8.90951C20.0066 8.98984 20 9.07226 20 9.15626C20 10.0043 20.6716 10.6918 21.5 10.6918C22.3284 10.6918 23 10.0043 23 9.15626C23 8.30819 22.3284 7.6207 21.5 7.6207C21.1309 7.6207 20.7929 7.7572 20.5315 7.98359L16.6362 7L15.2283 12.7651C13.3554 12.8913 11.671 13.4719 10.4003 14.3485C10.0395 13.9863 9.54524 13.7629 9 13.7629C7.89543 13.7629 7 14.6796 7 15.8103C7 16.5973 7.43366 17.2805 8.06967 17.6232C8.02372 17.8674 8 18.1166 8 18.3696C8 21.4792 11.5817 24 16 24C20.4183 24 24 21.4792 24 18.3696C24 18.1166 23.9763 17.8674 23.9303 17.6232C24.5663 17.2805 25 16.5973 25 15.8103C25 14.6796 24.1046 13.7629 23 13.7629C22.4548 13.7629 21.9605 13.9863 21.5997 14.3485C20.2153 13.3935 18.3399 12.7897 16.2647 12.7423L17.3638 8.24143L20.0193 8.90951ZM12.5 18.8815C13.3284 18.8815 14 18.194 14 17.3459C14 16.4978 13.3284 15.8103 12.5 15.8103C11.6716 15.8103 11 16.4978 11 17.3459C11 18.194 11.6716 18.8815 12.5 18.8815ZM19.5 18.8815C20.3284 18.8815 21 18.194 21 17.3459C21 16.4978 20.3284 15.8103 19.5 15.8103C18.6716 15.8103 18 16.4978 18 17.3459C18 18.194 18.6716 18.8815 19.5 18.8815ZM12.7773 20.503C12.5476 20.3462 12.2372 20.4097 12.084 20.6449C11.9308 20.8802 11.9929 21.198 12.2226 21.3548C13.3107 22.0973 14.6554 22.4686 16 22.4686C17.3446 22.4686 18.6893 22.0973 19.7773 21.3548C20.0071 21.198 20.0692 20.8802 19.916 20.6449C19.7628 20.4097 19.4524 20.3462 19.2226 20.503C18.3025 21.1309 17.1513 21.4449 16 21.4449C15.3173 21.4449 14.6345 21.3345 14 21.1137C13.5646 20.9621 13.1518 20.7585 12.7773 20.503Z" fill="white"/>
</svg>
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
