<div class="post-item">
    <h2 class="headline headline--small headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title()?></a></h2>
    <div class="metabox">
        <p>Posted by <?php echo get_the_author_posts_link()?> on <?php echo get_the_time('n.j.y')?> in <?php echo get_the_category_list(', ')?></p>
    </div>
    <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?php echo get_permalink()?>"> Continue reading &raquo;</a></p>

    </div>
</div>