<header class="banner navbar navbar-default navbar-static-top" role="banner">
  <div class="container">
      <div class="logo">
        <?php if (is_front_page() || is_home()) : ?>
          <h1><a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?> <?php bloginfo('description'); ?></a></h1>
        <?php else : ?>
          <a class="navbar-brand" href="<?= esc_url(home_url('/')); ?>"><?php bloginfo('name'); ?></a>
        <?php endif; ?>
      </div>
      <div class="navigation-container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only"><?= __('Toggle navigation', 'sage'); ?></span>
              <span class="hamburger">
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
                  <span class="icon-bar"></span>
              </span>
          </button>
        </div>
        <nav class="collapse navbar-collapse" role="navigation">
          <?php
          if (has_nav_menu('primary_navigation')) :
            wp_nav_menu(['theme_location' => 'primary_navigation', 'depth' => 3, 'walker' => new wp_bootstrap_navwalker(), 'menu_class' => 'nav navbar-nav']);
          endif;
          ?>
          <?php get_search_form(); ?>
        </nav>
      </div>
    </div>
</header>
