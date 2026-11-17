<?php
if (!defined('ABSPATH') || is_front_page()) {
  return;
}
$label = is_singular() ? get_the_title() : get_the_archive_title();
if (is_search()) $label = 'Busca';
if (is_404()) $label = 'Página não encontrada';
?>
<nav class="novanix-breadcrumb" aria-label="Localização">
  <a href="<?php echo esc_url(home_url('/')); ?>">Início</a>
  <?php if (is_page()): ?>
    <?php foreach (array_reverse(get_post_ancestors(get_queried_object_id())) as $id): ?>
      <span aria-hidden="true">/</span>
      <a href="<?php echo esc_url(get_permalink($id)); ?>"><?php echo esc_html(get_the_title($id)); ?></a>
    <?php endforeach; ?>
  <?php endif; ?>
  <?php if (is_singular('servicos')): ?>
    <span aria-hidden="true">/</span>
    <a href="<?php echo esc_url(get_post_type_archive_link('servicos')); ?>">Serviços</a>
  <?php endif; ?>
  <span aria-hidden="true">/</span>
  <span aria-current="page"><?php echo esc_html(wp_strip_all_tags($label)); ?></span>
</nav>
