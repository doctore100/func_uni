

<div class="post-item">
    <h2 class="headline headline--small headline--post-title"><a href="<?php the_permalink(); ?>"><?php the_title()?></a></h2>

    <div class="generic-content">
        <?php the_excerpt(); ?>
        <p><a class="btn btn--blue" href="<?php echo get_permalink()?>"> View Program &raquo;</a></p>

    </div>
</div>