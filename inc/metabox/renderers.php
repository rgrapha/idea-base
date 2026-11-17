<?php
if (!defined('ABSPATH')) {
  exit();
}

/**
 * Render dispatcher
 */
function novanix_campos_render_field($post, array $field): void
{
  $type = $field['type'] ?? 'text';

  // Compatibilidade com campo antigo do Odin
  if ($type === 'upload') {
    $type = 'media';
    $field['type'] = 'media';
  }
  $label = $field['label'] ?? '';
  $desc = $field['description'] ?? '';

  // title / separator NÃO precisam de id
  if ($type === 'title') {
    echo '<div class="campos-title">';
    echo '<h3 class="campos-title__text">' . esc_html($label ?: 'Seção') . '</h3>';
    if ($desc) {
      echo '<p class="campos-title__desc">' . esc_html($desc) . '</p>';
    }
    echo '</div>';
    return;
  }

  if ($type === 'separator') {
    echo '<hr class="campos-separator" />';
    return;
  }

  // A partir daqui, id é obrigatório
  if (empty($field['id'])) {
    // Evita warning e também te sinaliza no HTML que faltou id
    echo '<p style="color:#b32d2e;"><strong>Campo inválido:</strong> faltou "id".</p>';
    return;
  }

  $id = $field['id'];
  $label = $field['label'] ?? $id;
  $ph = $field['placeholder'] ?? '';
  $ui = $field['ui'] ?? [];

  $style = isset($ui['width']) ? 'width:' . esc_attr($ui['width']) . ';' : 'width:40%;';

  $value = get_post_meta($post->ID, $id, true);

  echo '<div class="campos-field campos-field--' . esc_attr($type) . '">';
  echo '  <div class="campos-field__label">';
  echo '    <label for="' . esc_attr($id) . '">' . esc_html($label) . '</label>';
  echo '  </div>';
  echo '  <div class="campos-field__control">';

  switch ($type) {
    case 'textarea':
      novanix_campos_render_textarea($id, $value, $ph, $style, $field);
      break;

    case 'url':
    case 'email':
    case 'tel':
    case 'color':
    case 'date':
    case 'time':
    case 'datetime-local':
    case 'search':
    case 'month':
    case 'week':
      novanix_campos_render_input($type, $id, $value, $ph, $style, $field);
      break;

    case 'gallery':
      novanix_campos_render_gallery($id, $value, $field);
      break;

    case 'datetime':
      novanix_campos_render_input('datetime-local', $id, $value, $ph, $style, $field);
      break;

    case 'range':
      novanix_campos_render_range($id, $value, $style, $field);
      break;

    case 'number':
      novanix_campos_render_number($id, $value, $ph, $style, $field);
      break;

    case 'checkbox':
      novanix_campos_render_checkbox($id, $value);
      break;

    case 'select':
      novanix_campos_render_select($id, $value, $field['options'] ?? [], $style);
      break;

    case 'radio':
      novanix_campos_render_radio($id, $value, $field['options'] ?? []);
      break;

    case 'editor':
      novanix_campos_render_editor($id, $value, $field);
      break;

    case 'upload':
    case 'media':
    case 'image':
    case 'image_plupload':
      novanix_campos_render_media($id, $value, $field);
      break;

    case 'repeater':
      novanix_campos_render_repeater($id, $value, $field);
      break;

    default:
      novanix_campos_render_input('text', $id, $value, $ph, $style, $field);
  }

  if ($desc) {
    echo '<p class="campos-field__desc">' . esc_html($desc) . '</p>';
  }

  echo '  </div>'; // .campos-field__control
  echo '</div>'; // .campos-field
}

/**
 * Render helpers
 */
function novanix_campos_render_input(
  string $type,
  string $id,
  $value,
  string $ph,
  string $style,
  array $field,
): void {
  $attrs = '';
  foreach (['min', 'max', 'step', 'pattern'] as $k) {
    if (isset($field[$k])) {
      $attrs .= ' ' . $k . '="' . esc_attr($field[$k]) . '"';
    }
  }
  // Máscara opcional (tel)
  if ($type === 'tel' && !empty($field['mask'])) {
    $attrs .= ' data-mask="' . esc_attr($field['mask']) . '"';
  }

  echo '<input type="' .
    esc_attr($type) .
    '" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr($value) .
    '" style="' .
    esc_attr($style) .
    '" placeholder="' .
    esc_attr($ph) .
    '"' .
    $attrs .
    ' />';
}

function novanix_campos_render_number(string $id, $value, string $ph, string $style, array $field): void
{
  $min = isset($field['min']) ? ' min="' . esc_attr($field['min']) . '"' : '';
  $max = isset($field['max']) ? ' max="' . esc_attr($field['max']) . '"' : '';
  $step = isset($field['step']) ? ' step="' . esc_attr($field['step']) . '"' : '';
  echo '<input type="number" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr($value) .
    '" style="' .
    esc_attr($style) .
    '" placeholder="' .
    esc_attr($ph) .
    '"' .
    $min .
    $max .
    $step .
    ' />';
}

function novanix_campos_render_textarea(string $id, $value, string $ph, string $style, array $field): void
{
  $rows = isset($field['rows']) ? (int) $field['rows'] : 3;
  echo '<textarea id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" rows="' .
    esc_attr($rows) .
    '" style="' .
    esc_attr($style) .
    '" placeholder="' .
    esc_attr($ph) .
    '">' .
    esc_textarea($value) .
    '</textarea>';
}

function novanix_campos_render_checkbox(string $id, $value): void
{
  $checked = (string) $value === '1' ? 'checked' : '';
  echo '<input type="checkbox" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="1" ' .
    $checked .
    ' />';
}

function novanix_campos_render_select(string $id, $value, array $options, string $style): void
{
  echo '<select id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" style="' .
    esc_attr($style) .
    '">';
  echo '<option value="">Selecione...</option>';
  foreach ($options as $k => $text) {
    $sel = (string) $value === (string) $k ? 'selected' : '';
    echo '<option value="' . esc_attr($k) . '" ' . $sel . '>' . esc_html($text) . '</option>';
  }
  echo '</select>';
}

function novanix_campos_render_radio(string $id, $value, array $options): void
{
  echo '<div style="display:flex;gap:14px;flex-wrap:wrap;">';
  foreach ($options as $k => $text) {
    $checked = (string) $value === (string) $k ? 'checked' : '';
    echo '<label style="display:flex;gap:6px;align-items:center;">';
    echo '<input type="radio" name="' .
      esc_attr($id) .
      '" value="' .
      esc_attr($k) .
      '" ' .
      $checked .
      ' />';
    echo '<span>' . esc_html($text) . '</span>';
    echo '</label>';
  }
  echo '</div>';
}

function novanix_campos_render_range(string $id, $value, string $style, array $field): void
{
  $min = $field['min'] ?? 0;
  $max = $field['max'] ?? 100;
  $step = $field['step'] ?? 1;

  $val = $value === '' || $value === null ? $min : $value;

  echo '<div style="display:flex;gap:10px;align-items:center;">';
  echo '<input type="range" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr($val) .
    '" min="' .
    esc_attr($min) .
    '" max="' .
    esc_attr($max) .
    '" step="' .
    esc_attr($step) .
    '" style="' .
    esc_attr($style) .
    '" />';
  echo '<span class="campos-range-value" data-for="' .
    esc_attr($id) .
    '" style="min-width:42px;">' .
    esc_html($val) .
    '</span>';
  echo '</div>';
}

function novanix_campos_render_editor(string $id, $value, array $field): void
{
  $settings = [
    'textarea_name' => $id,
    'textarea_rows' => $field['rows'] ?? 8,
    'media_buttons' => $field['media_buttons'] ?? false,
    'teeny' => $field['teeny'] ?? true,
  ];
  wp_editor((string) $value, sanitize_key($id), $settings);
}

function novanix_campos_render_media(string $id, $value, array $field): void
{
  $btn_select = $field['button_select'] ?? 'Selecionar';
  $btn_remove = $field['button_remove'] ?? 'Remover';
  $mime = $field['mime'] ?? '';

  $saved_value = $value;
  $preview = '';

  // Caso novo: salva attachment ID
  if (is_numeric($value) && (int) $value > 0) {
    $attachment_id = (int) $value;
    $url = wp_get_attachment_url($attachment_id);

    if ($mime === 'image' || $field['type'] === 'image' || $field['type'] === 'image_plupload') {
      $preview = wp_get_attachment_image($attachment_id, 'medium') ?: '';
    } else {
      $preview = $url
        ? '<a href="' . esc_url($url) . '" target="_blank">' . esc_html(basename($url)) . '</a>'
        : '';
    }

    $saved_value = $attachment_id;
  }

  // Caso antigo Odin: salva URL do arquivo
  elseif (is_string($value) && filter_var($value, FILTER_VALIDATE_URL)) {
    $preview =
      '<a href="' . esc_url($value) . '" target="_blank">' . esc_html(basename($value)) . '</a>';
    $saved_value = $value;
  }

  echo '<div class="campos-media-field" data-mime="' . esc_attr($mime) . '">';
  echo '<input type="hidden" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr($saved_value) .
    '" />';
  echo '<div class="campos-media-preview" style="margin-bottom:8px;">' . $preview . '</div>';
  echo '<button type="button" class="button campos-media-select" data-target="' .
    esc_attr($id) .
    '">' .
    esc_html($btn_select) .
    '</button> ';
  echo '<button type="button" class="button campos-media-remove" data-target="' .
    esc_attr($id) .
    '">' .
    esc_html($btn_remove) .
    '</button>';
  echo '</div>';
}

function novanix_campos_render_repeater(string $id, $value, array $field): void
{
  // value esperado: array de itens
  $items = is_array($value) ? $value : [];
  $subfields = $field['fields'] ?? []; // sub-campos
  $fixed_count = (int) ($field['fixed_count'] ?? 0);
  if ($fixed_count > 0) $items = array_pad(array_slice(array_values($items), 0, $fixed_count), $fixed_count, []);

  $btn_add = $field['button_add'] ?? 'Adicionar item';
  $btn_del = $field['button_del'] ?? 'Remover';

  echo '<div class="campos-repeater" data-field-id="' . esc_attr($id) . '">';
  echo '<input type="hidden" class="campos-repeater-store" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr(wp_json_encode($items)) .
    '">';

  echo '<div class="campos-repeater-items" style="display:grid;gap:10px;">';
  // Render itens existentes
  foreach ($items as $i => $row) {
    novanix_campos_render_repeater_row($id, (int) $i, (array) $row, $subfields, $btn_del, false, $fixed_count === 0);
  }
  echo '</div>';

  if ($fixed_count > 0) {
    echo '</div>';
    return;
  }

  // Template (JS clona)
  echo '<template class="campos-repeater-template">';
  novanix_campos_render_repeater_row($id, 0, [], $subfields, $btn_del, true);
  echo '</template>';

  echo '<button type="button" class="button campos-repeater-add">' .
    esc_html($btn_add) .
    '</button>';
  echo '</div>';
}

function novanix_campos_render_repeater_row(
  string $parent_id,
  int $index,
  array $row,
  array $subfields,
  string $btn_del,
  bool $is_template = false,
  bool $allow_remove = true,
): void {
  $idx = $is_template ? '__i__' : (string) $index;

  echo '<div class="campos-repeater-row' . ($allow_remove ? ' campos-repeater-row--removable' : '') . '" data-index="' . esc_attr($idx) . '">';
  echo '<div class="campos-repeater-grid">';

  foreach ($subfields as $sf) {
    $sid = $sf['id'];
    $stype = $sf['type'] ?? 'text';
    $slabel = $sf['label'] ?? $sid;
    $span = $sf['span'] ?? 6; // colunas (1..12)
    $ph = $sf['placeholder'] ?? '';
    $v = $row[$sid] ?? '';

    $name = $parent_id . '[' . $idx . '][' . $sid . ']';

    echo '<div style="grid-column: span ' . (int) $span . ';">';
    echo '<label class="campos-subfield__label">' . esc_html($slabel) . '</label>';

    // inputs simples dentro do repeater (text/number/url/tel/email)
    if (
      in_array(
        $stype,
        [
          'text',
          'number',
          'url',
          'tel',
          'email',
          'search',
          'date',
          'time',
          'month',
          'week',
          'color',
          'datetime-local',
        ],
        true,
      )
    ) {
      echo '<input class="campos-input" type="' .
        esc_attr($stype) .
        '" name="' .
        esc_attr($name) .
        '" value="' .
        esc_attr($v) .
        '" placeholder="' .
        esc_attr($ph) .
        '" style="width:100%;">';
    } elseif ($stype === 'textarea') {
      echo '<textarea name="' .
        esc_attr($name) .
        '" rows="2" placeholder="' .
        esc_attr($ph) .
        '" style="width:100%;">' .
        esc_textarea($v) .
        '</textarea>';
    } else {
      // fallback
      echo '<input type="text" name="' .
        esc_attr($name) .
        '" value="' .
        esc_attr($v) .
        '" placeholder="' .
        esc_attr($ph) .
        '" style="width:100%;">';
    }

    echo '</div>';
  }

  echo '</div>';
  if ($allow_remove) {
  echo '<div class="campos-repeater-actions">';
  echo '<button type="button" class="button campos-repeater-remove">' .
    esc_html($btn_del) .
    '</button>';
  echo '</div>';
  }
  echo '</div>';
}

/**
 * Sanitização central
 */
function novanix_campos_sanitize_field_value($raw, array $field)
{
  $type = $field['type'] ?? 'text';

  switch ($type) {
    case 'textarea':
      return sanitize_textarea_field((string) $raw);

    case 'gallery':
      // aceita "1,2,3" ou array
      if (is_string($raw)) {
        $ids = array_filter(array_map('intval', explode(',', $raw)));
      } elseif (is_array($raw)) {
        $ids = array_filter(array_map('intval', $raw));
      } else {
        $ids = [];
      }

      // salva como CSV
      return implode(',', $ids);

    case 'email':
      return sanitize_email((string) $raw);

    case 'tel':
      // mantém somente caracteres comuns de telefone
      return preg_replace('/[^0-9\+\-\(\)\s]/', '', (string) $raw);

    case 'url':
      return esc_url_raw((string) $raw);

    case 'color':
      // #RRGGBB ou vazio
      $c = (string) $raw;

      return preg_match('/^#[0-9a-fA-F]{6}$/', $c) ? $c : '';

    case 'number':
    case 'range':
      return is_numeric($raw) ? (string) $raw : '';

    case 'date':
    case 'time':
    case 'datetime-local':
    case 'month':
    case 'week':
    case 'search':
      return sanitize_text_field((string) $raw);

    case 'datetime':
      return sanitize_text_field((string) $raw);

    case 'select':
      $options = array_map('strval', array_keys($field['options'] ?? []));

      return in_array((string) $raw, $options, true) ? (string) $raw : '';

    case 'radio':
      $options = array_map('strval', array_keys($field['options'] ?? []));

      return in_array((string) $raw, $options, true) ? (string) $raw : '';

    /**
     * Upload antigo do Odin
     * Salva URL do arquivo
     */
    case 'upload':
      // Caso venha ID do anexo
      if (is_numeric($raw)) {
        $url = wp_get_attachment_url((int) $raw);

        return $url ? esc_url_raw($url) : '';
      }

      // Caso já venha URL
      if (is_string($raw) && filter_var($raw, FILTER_VALIDATE_URL)) {
        return esc_url_raw($raw);
      }

      return '';

    /**
     * Novo sistema
     * Salva ID do anexo
     */
    case 'media':
    case 'image':
    case 'image_plupload':
      if (is_numeric($raw)) {
        return (int) $raw;
      }

      if (is_string($raw) && filter_var($raw, FILTER_VALIDATE_URL)) {
        return esc_url_raw($raw);
      }

      return '';

    case 'editor':
      // permite HTML seguro
      return wp_kses_post((string) $raw);

    case 'repeater':
      // pode vir do hidden store (json) ou array do POST
      if (is_string($raw)) {
        $decoded = json_decode($raw, true);

        $raw = is_array($decoded) ? $decoded : [];
      }

      if (!is_array($raw)) {
        return [];
      }

      $sub = $field['fields'] ?? [];

      $clean = [];

      foreach ($raw as $row) {
        if (!is_array($row)) {
          continue;
        }

        $row_clean = [];

        foreach ($sub as $sf) {
          $sid = $sf['id'];

          $stype = $sf['type'] ?? 'text';

          $sv = $row[$sid] ?? '';

          // sanitização simples por tipo
          if ($stype === 'email') {
            $row_clean[$sid] = sanitize_email((string) $sv);
          } elseif ($stype === 'url') {
            $row_clean[$sid] = esc_url_raw((string) $sv);
          } elseif (in_array($stype, ['number', 'range'], true)) {
            $row_clean[$sid] = is_numeric($sv) ? (string) $sv : '';
          } elseif ($stype === 'textarea') {
            $row_clean[$sid] = sanitize_textarea_field((string) $sv);
          } else {
            $row_clean[$sid] = sanitize_text_field((string) $sv);
          }
        }

        $clean[] = $row_clean;
      }

      $fixed_count = (int) ($field['fixed_count'] ?? 0);
      if ($fixed_count > 0) {
        $empty = array_fill_keys(array_column($sub, 'id'), '');
        $clean = array_pad(array_slice($clean, 0, $fixed_count), $fixed_count, $empty);
      }
      return $clean;

    default:
      return sanitize_text_field((string) $raw);
  }
}

function novanix_campos_render_gallery(string $id, $value, array $field): void
{
  // salva como CSV de IDs: "12,45,90"
  $ids = [];

  if (is_string($value) && trim($value) !== '') {
    $ids = array_filter(array_map('intval', explode(',', $value)));
  } elseif (is_array($value)) {
    $ids = array_filter(array_map('intval', $value));
  }

  $btn_select = $field['button_select'] ?? 'Selecionar imagens';
  $btn_clear = $field['button_clear'] ?? 'Limpar';
  $max = isset($field['max']) ? (int) $field['max'] : 0; // 0 = sem limite

  echo '<div class="campos-gallery" data-max="' . esc_attr($max) . '">';
  echo '<input type="hidden" id="' .
    esc_attr($id) .
    '" name="' .
    esc_attr($id) .
    '" value="' .
    esc_attr(implode(',', $ids)) .
    '">';

  echo '<div class="campos-gallery-grid" style="display:flex;gap:10px;flex-wrap:wrap;margin-bottom:10px;">';
  foreach ($ids as $img_id) {
    $thumb = wp_get_attachment_image($img_id, 'thumbnail');
    if (!$thumb) {
      continue;
    }

    echo '<div class="campos-gallery-item" data-id="' .
      esc_attr($img_id) .
      '" style="position:relative;border:1px solid #eee;border-radius:8px;padding:6px;">';
    echo $thumb;
    echo '<button type="button" class="button campos-gallery-remove" style="position:absolute;top:6px;right:6px;">×</button>';
    echo '</div>';
  }
  echo '</div>';

  echo '<button type="button" class="button campos-gallery-select" data-target="' .
    esc_attr($id) .
    '">' .
    esc_html($btn_select) .
    '</button> ';
  echo '<button type="button" class="button campos-gallery-clear" data-target="' .
    esc_attr($id) .
    '">' .
    esc_html($btn_clear) .
    '</button>';

  echo '<p class="description" style="margin:8px 0 0;color:#666;">';
  echo 'Selecione múltiplas imagens. Arraste para reordenar (opcional, se ativar depois).';
  if ($max > 0) {
    echo ' Limite: ' . esc_html($max) . '.';
  }
  echo '</p>';

  echo '</div>';
}
