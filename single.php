<?php
if (!defined('ABSPATH')) {
  exit();
}
get_header();
?>
<main id="conteudo" class="novanix-shell">
  <?php get_template_part('templates/breadcrumb'); ?>
  <?php while (have_posts()): the_post(); ?>
    <article>
      <h1><?php the_title(); ?></h1>
      <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
      <?php if (is_singular('servicos') && novanix_meta('servico_subtitulo') !== ''): ?>
        <p><?php echo esc_html(novanix_meta('servico_subtitulo')); ?></p>
      <?php endif; ?>
      <?php the_content(); ?>
      <?php wp_link_pages(); ?>
      <?php if (is_singular('servicos') && novanix_meta('servico_link') !== ''): ?>
        <p><a href="<?php echo esc_url(novanix_meta('servico_link')); ?>">Saiba mais</a></p>
      <?php endif; ?>
    </article>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
