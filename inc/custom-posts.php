<?php
if (!defined('ABSPATH')) {
  exit();
}

add_action('init', 'novanix_register_content');
function novanix_register_content(): void
{
  // Exemplo público: renomeie antes de cadastrar conteúdo no projeto novo.
  register_post_type('servicos', [
    'labels' => [
      'name' => 'Serviços',
      'singular_name' => 'Serviço',
      'add_new_item' => 'Adicionar serviço',
      'edit_item' => 'Editar serviço',
    ],
    'public' => true,
    'has_archive' => true,
    'rewrite' => ['slug' => 'servicos'],
    'show_in_rest' => true,
    'menu_icon' => 'dashicons-portfolio',
    'supports' => ['title', 'editor', 'thumbnail', 'excerpt', 'revisions'],
  ]);

  register_taxonomy('categoria_servico', ['servicos'], [
    'labels' => [
      'name' => 'Categorias de serviços',
      'singular_name' => 'Categoria de serviço',
    ],
    'public' => true,
    'hierarchical' => true,
    'show_in_rest' => true,
    'show_admin_column' => true,
    'rewrite' => ['slug' => 'categoria-servico'],
  ]);

  // Dados globais têm administração, mas não geram URLs públicas.
  register_post_type('dados', [
    'labels' => [
      'name' => 'Dados do site',
      'singular_name' => 'Cadastro de dados',
    ],
    'public' => false,
    'show_ui' => true,
    'show_in_rest' => false,
    'has_archive' => false,
    'rewrite' => false,
    'menu_icon' => 'dashicons-admin-settings',
    'supports' => ['title'],
  ]);
}
