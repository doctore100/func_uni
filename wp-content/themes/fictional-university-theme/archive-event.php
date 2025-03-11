<?php get_header();
pageBanner( array(
	'title'    => 'All Events',
	'subtitle' => 'See whats going on in our wold'
) );
?>


    <div class="container container--narrow page-section">
		<?php
		while ( have_posts() ) {
			the_post();
            get_template_part( 'template-parts/content', 'event');

		 };
		echo paginate_links();
		?>
        <hr>
        <p> Looking for a recap of past events. <a href="<?php echo site_url() . '/pasts-events' ?>">Check put our past
                events archive</a></p>
    </div>

<?php get_footer(); ?>