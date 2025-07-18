<?php
if ( ! is_user_logged_in() ) {
	wp_redirect( esc_url( site_url( '/' ) ) );
	exit;
}

get_header();

while ( have_posts() ) {
	the_post();
	pageBanner();
	?>


    <div class="container container--narrow page-section">
        <div class="create-note">
            <h2 class="headline headline--small-plus">Create a New Note</h2>
            <label>
                <input type="text" class="new-note-title" placeholder="Title">
            </label>
            <label>
                <textarea class="new-note-body" placeholder="Note"></textarea>
            </label>
            <button class="btn btn--blue btn--small" id="create-note">
                <i class="fa fa-plus" aria-hidden="true"></i>
                Create Note
            </button>
            <span class="note-limit-message"> Note limit reached: Delete an existing Note to make room for a new one</span>
        </div>
        <ul class="min-list link-list" id="my-note">
			<?php
			$args      = array(
				'post_type'      => 'note',
				'author'         => get_current_user_id(),
				'posts_per_page' => - 1,
				'orderby'        => 'date',
				'order'          => 'DESC'
			);
			$userNotes = new WP_Query( $args );
			while ( $userNotes->have_posts() ) {
				$userNotes->the_post(); ?>
                <li data-id="<?php the_ID(); ?>">
                    <label>
                        <input readonly
                               value="<?php echo str_replace( 'Private: ', '', esc_attr( get_the_title() ) ); ?>"
                               class="note-title-field">
                    </label>
                    <span class="edit-note">
                        <i class="fa fa-pencil" aria-hidden="true"></i>
                        Edit
                    </span>

                    <span class="delete-note"> <i class="fa fa-trash-o" aria-hidden="true"></i>
                         Delete
                    </span>

                    <label>
                        <textarea readonly class="note-body-field">
                            <?php echo esc_textarea( wp_strip_all_tags( get_the_content() ) ); ?>
                        </textarea>
                    </label>

                    <span class="update-note btn btn--blue btn--small">
                        <i class="fa fa-arrow-right" aria-hidden="true"></i>
                        Save
                    </span>
                </li>
			<?php } ?>
        </ul>
    </div>


<?php }

get_footer();

?>