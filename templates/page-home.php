<?php
/** Template Name: Home */
if (!defined('ABSPATH')) {
  exit();
}
get_header();
?>
<main id="conteudo" class="novanix-shell">
  <?php while (have_posts()): the_post(); ?>
    <?php if (novanix_meta('home_subtitulo') !== ''): ?><p><?php echo esc_html(novanix_meta('home_subtitulo')); ?></p><?php endif; ?>
    <h1><?php echo esc_html(novanix_meta('home_titulo') ?: get_the_title()); ?></h1>
    <?php if (novanix_meta('home_texto') !== ''): ?><p><?php echo nl2br(esc_html(novanix_meta('home_texto'))); ?></p><?php endif; ?>
    <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
    <?php if (novanix_meta('home_botao_url') !== ''): ?><p><a href="<?php echo esc_url(novanix_meta('home_botao_url')); ?>">Saiba mais</a></p><?php endif; ?>
    <?php the_content(); ?>
    <?php
      $items = get_post_meta(get_the_ID(), 'home_indicadores', true);
      $timeline = get_post_meta(get_the_ID(), 'home_timeline', true);
    ?>
    <div class="novanix-grid">
      <?php foreach (is_array($items) ? $items : [] as $item): ?>
        <?php if (empty($item['valor']) && empty($item['legenda'])) continue; ?>
        <div><strong><?php echo esc_html($item['valor'] ?? ''); ?></strong><p><?php echo esc_html($item['legenda'] ?? ''); ?></p></div>
      <?php endforeach; ?>
    </div>
    <?php if (is_array($timeline) && $timeline): ?>
      <ol>
        <?php foreach ($timeline as $item): ?>
          <li><strong><?php echo esc_html($item['ano'] ?? ''); ?></strong> <?php echo nl2br(esc_html($item['texto'] ?? '')); ?></li>
        <?php endforeach; ?>
      </ol>
    <?php endif; ?>
  <?php endwhile; ?>
  <?php
    $featured = new WP_Query([
      'post_type' => 'servicos',
      'post_status' => 'publish',
      'posts_per_page' => 4,
      'meta_key' => 'servico_destaque',
      'meta_value' => '1',
      'no_found_rows' => true,
    ]);
  ?>
  <?php if ($featured->have_posts()): ?>
    <section><h2>Em destaque</h2><div class="novanix-grid">
      <?php while ($featured->have_posts()): $featured->the_post(); ?>
        <?php get_template_part('templates/content-card'); ?>
      <?php endwhile; ?>
    </div></section>
  <?php endif; ?>
  <?php wp_reset_postdata(); ?>
</main>
<?php get_footer(); ?>
