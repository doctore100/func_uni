<div class="full-width-split group">
    <div class="full-width-split__one">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">Upcoming Events</h2>
			<?php
			$today          = date( 'Ymd' );
			$homePageEvents = new WP_Query( array(
				'post_type'      => 'event',
				'posts_per_page' => 2,
				'meta_key'       => 'event_date',
				'orderby'        => 'meta_value_num',
				'order'          => 'ASC',
				'meta_query'     => array(
					array(
						'key'     => 'event_date',
						'compare' => '>=',
						'value'   => $today,
						'type'    => 'numeric'
					)
				)
			) );
			while ( $homePageEvents->have_posts() ) {
				$homePageEvents->the_post();
				get_template_part( 'template-parts/content', 'event' );
			}
			wp_reset_postdata() ?>


            <p class="t-center no-margin"><a href="<?php echo get_post_type_archive_link( 'event' ) ?>"
                                             class="btn btn--blue">View All Events</a></p>
        </div>
    </div>
    <div class="full-width-split__two">
        <div class="full-width-split__inner">
            <h2 class="headline headline--small-plus t-center">From Our Blogs</h2>
			<?php
			$homePageHome = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 2,

			) );
			while ( $homePageHome->have_posts() ) {
				$homePageHome->the_post(); ?>
                <div class="event-summary">
                    <a class="event-summary__date event-summary__date--beige t-center"
                       href="<?php the_permalink(); ?>">
                        <span class="event-summary__month"><?php the_time( 'M' ); ?></span>
                        <span class="event-summary__day"><?php the_time( 'd' ); ?></span>
                    </a>
                    <div class="event-summary__content">
                        <h5 class="event-summary__title headline headline--tiny"><a
                                    href="<?php the_permalink(); ?>"><?php the_title() ?></a></h5>
                        <p><?php echo wp_trim_words( get_the_content(), 20 ); ?> <a href="<?php the_permalink(); ?>"
                                                                                    class="nu gray">Read more</a>
                        </p>
                    </div>
                </div>
			<?php }
			wp_reset_postdata(); ?>


            <p class="t-center no-margin"><a href="<?php echo site_url() . '/blog' ?>" class="btn btn--yellow">View
                    All Blog Posts</a></p>
        </div>
    </div>
</div>

