<?php
if (!defined('ABSPATH')) {
  exit();
}
$data = novanix_site_data();
$whatsapp = novanix_whatsapp_url($data['site_whatsapp']);
$phone = novanix_phone_url($data['site_telefone']);
?>
<footer class="novanix-shell novanix-footer">
  <?php if ($whatsapp !== ''): ?>
    <p><a href="<?php echo esc_url($whatsapp); ?>">WhatsApp: <?php echo esc_html($data['site_whatsapp']); ?></a></p>
  <?php endif; ?>
  <?php if ($phone !== ''): ?>
    <p><a href="<?php echo esc_url($phone); ?>">Telefone: <?php echo esc_html($data['site_telefone']); ?></a></p>
  <?php endif; ?>
  <?php if (is_email($data['site_email'])): ?>
    <p><a href="<?php echo esc_url('mailto:' . $data['site_email']); ?>"><?php echo esc_html($data['site_email']); ?></a></p>
  <?php endif; ?>
  <?php if ($data['site_endereco'] !== ''): ?>
    <address>
      <?php if ($data['site_mapa_url'] !== ''): ?><a href="<?php echo esc_url($data['site_mapa_url']); ?>"><?php endif; ?>
      <?php echo nl2br(esc_html($data['site_endereco'])); ?>
      <?php if ($data['site_mapa_url'] !== ''): ?></a><?php endif; ?>
    </address>
  <?php endif; ?>
  <p><?php echo esc_html(get_bloginfo('name')); ?></p>
</footer>
<?php wp_footer(); ?>
</body>
</html>
