<?php
if (!defined('ABSPATH')) {
  exit();
}

add_action('after_setup_theme', function () {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  register_nav_menus(['principal' => 'Menu principal']);
});

add_action('wp_enqueue_scripts', function () {
  $file = get_stylesheet_directory() . '/assets/css/theme.css';
  wp_enqueue_style('novanix', get_stylesheet_directory_uri() . '/assets/css/theme.css', [], filemtime($file));
  $script = get_stylesheet_directory() . '/assets/js/theme.js';
  wp_enqueue_script('novanix', get_stylesheet_directory_uri() . '/assets/js/theme.js', [], filemtime($script), true);
}, 30);

add_action('pre_get_posts', function ($query) {
  if (!is_admin() && $query->is_main_query() && $query->is_post_type_archive('servicos')) {
    $query->set('posts_per_page', 8);
  }
});
