<?php
/**
 * Template Name: Contact Us
 */

$sent    = false;
$errors  = [];
$fields  = [ 'contact_name' => '', 'contact_email' => '', 'contact_subject' => '', 'contact_message' => '' ];

if ( $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['contact_nonce'] ) ) {
    if ( ! wp_verify_nonce( $_POST['contact_nonce'], 'news1_contact' ) ) {
        $errors[] = 'Security check failed. Please refresh and try again.';
    } else {
        $fields['contact_name']    = sanitize_text_field( $_POST['contact_name'] ?? '' );
        $fields['contact_email']   = sanitize_email( $_POST['contact_email'] ?? '' );
        $fields['contact_subject'] = sanitize_text_field( $_POST['contact_subject'] ?? '' );
        $fields['contact_message'] = sanitize_textarea_field( $_POST['contact_message'] ?? '' );

        if ( empty( $fields['contact_name'] ) )    $errors[] = 'Name is required.';
        if ( ! is_email( $fields['contact_email'] ) ) $errors[] = 'A valid email address is required.';
        if ( empty( $fields['contact_subject'] ) ) $errors[] = 'Subject is required.';
        if ( empty( $fields['contact_message'] ) ) $errors[] = 'Message is required.';

        if ( empty( $errors ) ) {
            $saved = news1_save_contact_message(
                $fields['contact_name'],
                $fields['contact_email'],
                $fields['contact_subject'],
                $fields['contact_message']
            );

            if ( $saved && ! is_wp_error( $saved ) ) {
                // Try to send email notification — failure is non-fatal
                $headers = [
                    'Content-Type: text/plain; charset=UTF-8',
                    'Reply-To: ' . $fields['contact_name'] . ' <' . $fields['contact_email'] . '>',
                ];
                wp_mail(
                    get_option( 'admin_email' ),
                    '[Contact] ' . $fields['contact_subject'],
                    "Name: {$fields['contact_name']}\nEmail: {$fields['contact_email']}\n\n{$fields['contact_message']}",
                    $headers
                );

                $sent   = true;
                $fields = [ 'contact_name' => '', 'contact_email' => '', 'contact_subject' => '', 'contact_message' => '' ];
            } else {
                $errors[] = 'Could not save your message. Please try again.';
            }
        }
    }
}

get_header();
?>

<main id="primary" role="main">
<div class="contact-page-wrap">

    <div class="contact-info-col">
        <h1 class="contact-page-title"><?php the_title(); ?></h1>
        <p class="contact-intro">
            <?php _e( 'Have a tip, question, or feedback? Fill out the form and we\'ll get back to you as soon as possible.', 'news-1' ); ?>
        </p>

        <div class="contact-info-items">
            <div class="contact-info-item">
                <span class="contact-info-icon">&#9993;</span>
                <div>
                    <div class="contact-info-label"><?php _e( 'Email', 'news-1' ); ?></div>
                    <div class="contact-info-value"><?php echo antispambot( get_option( 'admin_email' ) ); ?></div>
                </div>
            </div>
        </div>
    </div>

    <div class="contact-form-col">

        <?php if ( $sent ) : ?>
        <div class="contact-success">
            <span>&#10003;</span>
            <?php _e( 'Your message has been sent. Thank you!', 'news-1' ); ?>
        </div>
        <?php endif; ?>

        <?php if ( $errors ) : ?>
        <div class="contact-error-list">
            <?php foreach ( $errors as $e ) : ?>
            <div class="contact-error-item">&#9679; <?php echo esc_html( $e ); ?></div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <form class="contact-form" method="post" novalidate>
            <?php wp_nonce_field( 'news1_contact', 'contact_nonce' ); ?>

            <div class="contact-form-row contact-form-row--half">
                <div class="contact-field">
                    <label for="contact_name"><?php _e( 'Your Name', 'news-1' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="text" id="contact_name" name="contact_name"
                           value="<?php echo esc_attr( $fields['contact_name'] ); ?>"
                           required autocomplete="name" maxlength="100">
                </div>
                <div class="contact-field">
                    <label for="contact_email"><?php _e( 'Email Address', 'news-1' ); ?> <span aria-hidden="true">*</span></label>
                    <input type="email" id="contact_email" name="contact_email"
                           value="<?php echo esc_attr( $fields['contact_email'] ); ?>"
                           required autocomplete="email" maxlength="200">
                </div>
            </div>

            <div class="contact-field">
                <label for="contact_subject"><?php _e( 'Subject', 'news-1' ); ?> <span aria-hidden="true">*</span></label>
                <input type="text" id="contact_subject" name="contact_subject"
                       value="<?php echo esc_attr( $fields['contact_subject'] ); ?>"
                       required maxlength="200">
            </div>

            <div class="contact-field">
                <label for="contact_message"><?php _e( 'Message', 'news-1' ); ?> <span aria-hidden="true">*</span></label>
                <textarea id="contact_message" name="contact_message" rows="7" required><?php echo esc_textarea( $fields['contact_message'] ); ?></textarea>
            </div>

            <button type="submit" class="contact-submit">
                <?php _e( 'Send Message', 'news-1' ); ?>
            </button>
        </form>

    </div>
</div>
</main>

<?php get_footer(); ?>
