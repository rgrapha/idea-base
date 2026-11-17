<?php
if (!defined('ABSPATH')) {
  exit();
}

function novanix_meta(string $key, int $post_id = 0): string
{
  $value = get_post_meta($post_id ?: get_the_ID(), $key, true);
  return is_scalar($value) ? trim((string) $value) : '';
}

function novanix_site_data(): array
{
  $ids = get_posts([
    'post_type' => 'dados',
    'post_status' => 'publish',
    'numberposts' => 1,
    'orderby' => 'ID',
    'order' => 'ASC',
    'fields' => 'ids',
  ]);
  $data = [];
  foreach (['site_whatsapp', 'site_telefone', 'site_email', 'site_endereco', 'site_mapa_url'] as $key) {
    $data[$key] = $ids ? novanix_meta($key, (int) $ids[0]) : '';
  }
  return $data;
}

function novanix_whatsapp_url(string $number): string
{
  $digits = preg_replace('/\D/', '', $number);
  if (in_array(strlen($digits), [10, 11], true)) {
    $digits = '55' . $digits;
  }
  return $digits !== '' ? 'https://wa.me/' . $digits : '';
}

function novanix_phone_url(string $number): string
{
  $digits = preg_replace('/\D/', '', $number);
  return $digits !== '' ? 'tel:' . (str_starts_with(trim($number), '+') ? '+' : '') . $digits : '';
}
