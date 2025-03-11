<?php
// Include WordPress header
get_header();

// Custom page banner (if reusable)
pageBanner();
?>

    <div class="container container--narrow page-section">
        <section class="error-404 not-found">
            <header class="page-header">
                <h1 class="page-title">Oops! Page Not Found</h1>
            </header><!-- .page-header -->

            <div class="page-content">
                <p>We couldn't find the page you were looking for. It might have been removed, or the URL might be incorrect.</p>
                <p>Here are some helpful links to get you back on track:</p>
                <ul class="helpful-links">
                    <li><a href="<?php echo home_url(); ?>">Go to Homepage</a></li>
                    <li><a href="<?php echo site_url('/blog'); ?>">Visit Our Blog</a></li>
                    <li><a href="<?php echo site_url('/contact'); ?>">Contact Us</a></li>
                </ul>
                <p>Or try searching for the content:</p>
				<?php get_search_form(); ?>
            </div><!-- .page-content -->
        </section><!-- .error-404 -->
    </div><!-- .container -->

<?php
// Include WordPress footer
get_footer();
?>