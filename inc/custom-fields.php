<?php
if (!defined('ABSPATH')) {
  exit();
}

require_once get_stylesheet_directory() . '/inc/metabox/config.php';
require_once get_stylesheet_directory() . '/inc/metabox/renderers.php';

add_action(
  'add_meta_boxes',
  function ($post_type, $post) {
    foreach (novanix_campos_metaboxes() as $mb) {
      $mb_post_type = $mb['post_type'];

      $allowed = is_array($mb_post_type)
        ? in_array($post_type, $mb_post_type, true)
        : $post_type === $mb_post_type;

      if (!$allowed) {
        continue;
      }

      if (!empty($mb['template']) && $post instanceof WP_Post) {
        $current_template = get_page_template_slug($post->ID);

        if ($current_template !== $mb['template']) {
          continue;
        }
      }

      add_meta_box(
        $mb['id'],
        $mb['title'],
        function ($post) use ($mb) {
          novanix_campos_metabox_render($post, $mb);
        },
        $mb['post_type'],
        $mb['context'] ?? 'normal',
        $mb['priority'] ?? 'high',
      );
    }
  },
  10,
  2,
);

add_action('admin_enqueue_scripts', function ($hook) {
  if (!in_array($hook, ['post.php', 'post-new.php'], true)) {
    return;
  }

  wp_enqueue_media();

  wp_enqueue_script(
    'campos-metabox-admin',
    get_stylesheet_directory_uri() . '/inc/metabox/admin.js',
    ['jquery'],
    filemtime(get_stylesheet_directory() . '/inc/metabox/admin.js'),
    true,
  );
});

add_action('save_post', function ($post_id) {
  if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
    return;
  }
  if (wp_is_post_revision($post_id)) {
    return;
  }

  foreach (novanix_campos_metaboxes() as $mb) {
    $post_type = get_post_type($post_id);
    $mb_post_type = $mb['post_type'];

    $allowed = is_array($mb_post_type)
      ? in_array($post_type, $mb_post_type, true)
      : $post_type === $mb_post_type;

    if (!$allowed) {
      continue;
    }

    if (!empty($mb['template']) && get_page_template_slug($post_id) !== $mb['template']) {
      continue;
    }

    $nonce_name = $mb['id'] . '_nonce';
    $nonce_action = $mb['id'] . '_save';

    if (!isset($_POST[$nonce_name]) || !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST[$nonce_name])), $nonce_action)) {
      continue;
    }
    if (!current_user_can('edit_post', $post_id)) {
      continue;
    }

    foreach ($mb['fields'] as $field) {
      $type = $field['type'] ?? 'text';

      // Campos decorativos
      if (in_array($type, ['title', 'separator'], true)) {
        continue;
      }

      // id obrigatório para salvar
      if (empty($field['id'])) {
        continue;
      }

      $key = $field['id'];

      // checkbox sempre salva
      if ($type === 'checkbox') {
        update_post_meta($post_id, $key, isset($_POST[$key]) ? '1' : '0');
        continue;
      }

      // ✅ gallery sempre salva (mesmo vazio)
      if ($type === 'gallery') {
        $raw = wp_unslash($_POST[$key] ?? ''); // "1,2,3" ou ""
        update_post_meta($post_id, $key, wp_slash(novanix_campos_sanitize_field_value($raw, $field)));
        continue;
      }

      // repeater sempre salva
      if ($type === 'repeater') {
        $raw = wp_unslash($_POST[$key] ?? []);
        update_post_meta($post_id, $key, wp_slash(novanix_campos_sanitize_field_value($raw, $field)));
        continue;
      }

      // editor sempre salva
      if ($type === 'editor') {
        $raw = wp_unslash($_POST[$key] ?? '');
        update_post_meta($post_id, $key, wp_slash(novanix_campos_sanitize_field_value($raw, $field)));
        continue;
      }

      // Demais: só salva se veio no POST
      if (!array_key_exists($key, $_POST)) {
        continue;
      }

      $raw = wp_unslash($_POST[$key]);
      update_post_meta($post_id, $key, wp_slash(novanix_campos_sanitize_field_value($raw, $field)));
    }
  }
});

function novanix_campos_metabox_render($post, array $mb): void
{
  $nonce_name = $mb['id'] . '_nonce';
  $nonce_action = $mb['id'] . '_save';
  wp_nonce_field($nonce_action, $nonce_name);

  // CSS do layout (padroniza todos os campos)
  echo '<style>
    .campos-metabox{display:grid;gap:14px;}
    .campos-title{padding-top:8px;}
    .campos-title__text{margin:0;font-size:14px;}
    .campos-title__desc{margin:6px 0 0;color:#666;}
    .campos-separator{border:none;border-top:1px solid #eee;margin:6px 0;}

    .campos-field{
      display:grid;     
      gap:5px;
      align-items:start;     
    }
    .campos-field:last-child{border-bottom:none;}
    .campos-field__label label{font-weight:600;display:block;line-height:1.2;}
    .campos-field__control{min-width:0;}
    .campos-field__desc{margin:6px 0 0;color:#666;}

    .campos-field__control input[type="text"],
    .campos-field__control input[type="email"],
    .campos-field__control input[type="tel"],
    .campos-field__control input[type="url"],
    .campos-field__control input[type="number"],
    .campos-field__control input[type="search"],
    .campos-field__control input[type="date"],
    .campos-field__control input[type="time"],
    .campos-field__control input[type="month"],
    .campos-field__control input[type="week"],
    .campos-field__control input[type="color"],
    .campos-field__control input[type="datetime-local"],
    .campos-field__control input[type="range"],
    .campos-field__control select,
    .campos-field__control textarea{width:100%;max-width:520px;}

    .campos-field--checkbox .campos-field__control{padding-top:2px;}

    /* Repeater */
    .campos-repeater-grid{display:grid;grid-template-columns: repeat(12, 1fr); gap:10px;}
    .campos-repeater-row{border:1px solid #eee;border-radius:8px;padding:10px;background:#fff;}
    .campos-repeater-row--removable{display:grid;grid-template-columns:minmax(0,1fr) auto;gap:12px;align-items:end;}
    .campos-repeater-grid{min-width:0;}
    .campos-repeater-actions{display:flex;align-items:center;justify-content:flex-end;}
    .campos-repeater-grid input,.campos-repeater-grid textarea{box-sizing:border-box;max-width:100%;margin:0;}
    @media(max-width:600px){.campos-repeater-row--removable{grid-template-columns:minmax(0,1fr);}.campos-repeater-grid>div{grid-column:span 12!important;}}
    .campos-subfield__label{font-weight:600;display:block;margin-bottom:6px;}
    .campos-input{width:100%;}
  </style>';

  echo '<div class="campos-metabox">';
  foreach ($mb['fields'] as $field) {
    novanix_campos_render_field($post, $field);
  }
  echo '</div>';
}
