<?php
if (!defined('ABSPATH')) {
  exit();
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo('charset'); ?>">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="novanix-skip" href="#conteudo">Pular para o conteúdo</a>
<header class="novanix-shell novanix-header">
  <a href="<?php echo esc_url(home_url('/')); ?>"><?php echo esc_html(get_bloginfo('name')); ?></a>
  <?php wp_nav_menu(['theme_location' => 'principal', 'container' => 'nav', 'fallback_cb' => false]); ?>
</header>
