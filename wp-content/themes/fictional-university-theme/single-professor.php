<?php

get_header();

while ( have_posts() ) {
	the_post();
	pageBanner();
	?>


    <div class="container container--narrow page-section">


        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
					<?php the_post_thumbnail( 'professorPortrait' ); ?>

                </div>
                <div class="two-thirds">
					<?php
					$likeArray  = array(
						'post_type'  => 'like',
						'meta_query' => array(
							array(
								'key'     => 'liked_professor_id',
								'compare' => '=',
								'value'   => get_the_ID(),
							)
						)
					);
					$existArray = array(
						'author'     => get_current_user_id(),
						'post_type'  => 'like',
						'meta_query' => array(
							array(
								'key'     => 'liked_professor_id',
								'compare' => '=',
								'value'   => get_the_ID(),
							)
						)
					);

					$likesQuery  = new WP_Query( $likeArray );
					$existQuery  = new WP_Query( $existArray );
					$existStatus = $existQuery->found_posts ? 'yes' : 'no';
					?>
                    <span class="like-box" data-like="<?php echo $existQuery->posts[0]->ID ?? ''; ?>" data-exists="<?php echo is_user_logged_in() ? $existStatus : 'no'; ?>"
                          data-professor="<?php the_ID(); ?>">
                        <i class="fa fa-heart-o" aria-hidden="true"></i>
                        <i class="fa fa-heart" aria-hidden="true"></i>
                        <span class="like-count"><?php echo $likesQuery->found_posts ?></span>
                    </span>
					<?php the_content(); ?>
                </div>
            </div>

        </div>
		<?php
		$relatedPrograms = get_field( 'related_program' );
		if ( $relatedPrograms ) {
			echo '<hr>';
			echo '<h2 class="headline headline--small">Subject(s) Taught</h2>';
			echo '<ul class=" min-list min-list">';
			foreach ( $relatedPrograms as $program ) { ?>
                <li><a href="<?php echo get_permalink( $program ) ?>"> <?php echo get_the_title( $program->ID ); ?></a>
                </li>

			<?php }
			echo '</ul>';


		} ?>


    </div>


<?php }

get_footer();

?>