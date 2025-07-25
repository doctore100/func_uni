

<header class="site-header">
    <div class="container">
        <h1 class="school-logo-text float-left">
            <a href="<?php echo site_url() ?>">
                <strong>
                    Fictional
                </strong>
                University
            </a>
        </h1>
        <a href="<?php echo esc_url(site_url('/search')) ?>" class="js-search-trigger site-header__search-trigger">
            <i class="fa fa-search" aria-hidden="true"></i>
        </a>
        <i class="site-header__menu-trigger fa fa-bars" aria-hidden="true"></i>
        <div class="site-header__menu group">
            <nav class="main-navigation">
                <!--                show the menus in from page is created in the admin-->
                <!--                --><?php //wp_nav_menu( array( 'theme_location' => 'headerMenuLocation' ) ); ?>
                <ul>
                    <li <?php if (is_page('about-us') or wp_get_post_parent_id())
                        echo 'class=current-menu-item' ?>>
                        <a href="<?php echo site_url() . '/about-us' ?>">
                            About Us
                        </a>
                    </li>
                    <li <?php if (get_post_type() == 'program') echo 'class=current-menu-item' ?> >

                        <a href="<?php echo get_post_type_archive_link('program') ?>">
                            Programs
                        </a>
                    </li>

                    <li <?php if (get_post_type() == 'event' or is_page('pasts-events'))
                        echo 'class=current-menu-item' ?>>
                        <a href="<?php echo get_post_type_archive_link('event') ?>">
                            Events
                        </a>
                    </li>
                    <li <?php if (get_post_type() == 'campus') echo 'class=current-menu-item' ?> >
                        <a  href="<?php echo get_post_type_archive_link('campus') ?>">
                            Campuses
                        </a>
                    </li>

                    <li <?php if (get_post_type() == 'post')
                        echo 'class=current-menu-item' ?>><a href="<?php echo site_url() . '/blog' ?>">Blog</a></li>
                </ul>
            </nav>
            <div class="site-header__util">
                <?php if (is_user_logged_in()) { ?>
                    <a href="<?php echo esc_url(site_url() . '/my-note') ?>"
                       class="btn btn--small btn--orange float-left push-right">
                        My Note
                    </a>

                    <a href="<?php echo wp_logout_url() ?>"
                       class="btn btn--small btn--dark-orange float-left btn--with-photo">
                        <span class="site-header__avatar">
                            <?php get_avatar(get_current_user_id(), 60) ?>
                        </span>
                        <span class="bts__text">
                            Log Out
                        </span>
                    </a>
                <?php } else { ?>
                    <a href="<?php echo wp_logout_url() ?>" class="btn btn--small btn--orange float-left push-right">
                        Login
                    </a>
                    <a href="<?php echo wp_registration_url() ?>" class="btn btn--small btn--dark-orange float-left">
                        Sign Up
                    </a>

                <?php } ?>
                <a href="<?php echo esc_url(site_url('/search')); ?>" class="search-trigger js-search-trigger">
                    <i class="fa fa-search" aria-hidden="true"></i>
                </a>
            </div>
        </div>
    </div>
</header>