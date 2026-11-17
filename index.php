<?php
if (!defined('ABSPATH')) {
  exit();
}
get_header();
?>
<main id="conteudo" class="novanix-shell">
  <?php get_template_part('templates/breadcrumb'); ?>
  <h1><?php echo esc_html(is_search() ? 'Resultados da busca' : (is_archive() ? wp_strip_all_tags(get_the_archive_title()) : 'Publicações')); ?></h1>
  <?php if (have_posts()): ?>
    <div class="novanix-grid">
      <?php while (have_posts()): the_post(); ?>
        <?php get_template_part('templates/content-card'); ?>
      <?php endwhile; ?>
    </div>
    <?php the_posts_pagination(); ?>
  <?php else: ?>
    <p>Nenhum conteúdo encontrado.</p>
  <?php endif; ?>
</main>
<?php get_footer(); ?>
