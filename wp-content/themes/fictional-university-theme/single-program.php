<?php

get_header();

while (have_posts()) {
    the_post();
    pageBanner();
    ?>


    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?php echo get_post_type_archive_link('program') ?>">
                    <i class="fa fa-home" aria-hidden="true">
                        All Programs
                    </i>
                </a>
                <span class="metabox__main">
                    <?php the_title(); ?>
                </span>
            </p>
        </div>

        <div class="generic-content"><?php the_field('main_body_content'); ?></div>
        <?php
        $relatedProfessor = new WP_Query(array(
            'post_type' => 'professor',
            'posts_per_page' => -1,
            'orderby' => 'title',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'related_program',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ));
        if ($relatedProfessor->have_posts()) {
            echo '<hr class="section-break section-break--medium">';
            echo '<h3>  ' . get_the_title() . ' Professor </h3>';


            echo '<ul class="professor-cards">';
            while ($relatedProfessor->have_posts()) {
                $relatedProfessor->the_post(); ?>
                <li class="professor-card__list-item">
                    <a class="professor-card" href="<?php the_permalink(); ?>">
                        <img class="professor-card__image" src="<?php the_post_thumbnail_url('professorLandscape'); ?>">
                        <span class="professor-card__name"><?php the_title() ?></span>
                    </a>
                </li>

            <?php }
            echo '</ul>';
            wp_reset_postdata();
        }
        $today = date('Ymd');
        $homePageEvents = new WP_Query(array(
            'post_type' => 'event',
            'posts_per_page' => 2,
            'meta_key' => 'event_date',
            'orderby' => 'meta_value_num',
            'order' => 'ASC',
            'meta_query' => array(
                array(
                    'key' => 'event_date',
                    'compare' => '>=',
                    'value' => $today,
                    'type' => 'numeric'
                ),
                array(
                    'key' => 'related_program',
                    'compare' => 'LIKE',
                    'value' => '"' . get_the_ID() . '"'
                )
            )
        ));
        if ($homePageEvents->have_posts()) {
            echo '<hr class="section-break ">';
            echo '<h2> 
                    Upcoming ' . get_the_title() . ' Events 
                  </h2>';
            while ($homePageEvents->have_posts()) {
                $homePageEvents->the_post();
                get_template_part('template-parts/content', 'event');
            }
            wp_reset_postdata();
        }
        $relatedCampuses = get_field('related_campus');
        if ($relatedCampuses) {
            echo '<hr class="section-break ">';
            echo '<h2 class="headline headline--small">'
                . get_the_title() . ' is Available At these Campuses ' .
                '</h2>';
            foreach ($relatedCampuses as $campus) { ?>
                <li>
                    <a href="<?php echo get_the_permalink($campus) ?>">
                        <?php echo get_the_title($campus) ?>
                    </a>
                </li>

                <?php
            }
        }
        ?>


    </div>


<?php }
get_footer(); ?>