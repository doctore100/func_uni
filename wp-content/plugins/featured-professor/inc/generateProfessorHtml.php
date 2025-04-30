<?php

function generateProfessorHtml( $profId ): string {
	$professorPost = new WP_Query( [
		'post_type' => 'professor',
		'p' => $profId
	] );
	while ( $professorPost->have_posts() ) {
		$professorPost->the_post();
		ob_start(); ?>
        <div class="professor-callout">
            <div class="professor-callout__photo" style="background-image: url(<?php the_post_thumbnail_url('professorPorteait');?>)"></div>
            <div class="professor-callout__text">
                <h5><?php the_title(); ?></h5>
                <p> <?php echo wp_trim_words( get_the_content(), 30 ) ?>

                </p>

                <?php
                $relatedPrograms = get_field('related_program');
                if( $relatedPrograms ): ?>
                    <p> Name Teachers: <?php foreach ($relatedPrograms as $key => $program){
                        echo  get_the_title($program);
                        if( $key != array_key_last($relatedPrograms) && count($relatedPrograms) > 1 ) {
                            echo ', ';
                        }
	                    }?>. </p>

                   <?php endif; ?>
                <a href="<?php the_permalink(); ?>" class="button">Learn More about <?php the_title()?></a>
            </div>

        </div>
		<?php
		wp_reset_postdata();
		return ob_get_clean();
	}

}