<?php
if (!defined('ABSPATH')) {
  exit();
}

function novanix_campos_metaboxes(): array
{
  return [
    [
      'id' => 'home-apresentacao',
      'title' => 'Apresentação',
      'post_type' => 'page',
      'template' => 'templates/page-home.php',
      'context' => 'normal',
      'priority' => 'high',
      'fields' => [
        [
          'id' => 'home_subtitulo',
          'label' => 'Subtítulo',
          'type' => 'text',
        ],
        [
          'id' => 'home_titulo',
          'label' => 'Título',
          'type' => 'text',
        ],
        [
          'id' => 'home_texto',
          'label' => 'Texto',
          'type' => 'textarea',
        ],
        [
          'id' => 'home_botao_url',
          'label' => 'Link do botão',
          'type' => 'url',
        ],
        [
          'type' => 'separator',
        ],
        [
          'type' => 'title',
          'label' => 'Linha do tempo',
        ],
        [
          'id' => 'home_timeline',
          'label' => 'Acontecimentos',
          'type' => 'repeater',
          'button_add' => 'Adicionar acontecimento',
          'fields' => [
            [
              'id' => 'ano',
              'label' => 'Ano / período',
              'type' => 'text',
              'span' => 3,
            ],
            [
              'id' => 'texto',
              'label' => 'Acontecimento',
              'type' => 'textarea',
              'span' => 9,
            ],
          ],
        ],
        [
          'id' => 'home_indicadores',
          'label' => 'Quatro indicadores fixos',
          'type' => 'repeater',
          'fixed_count' => 4,
          'fields' => [
            [
              'id' => 'valor',
              'label' => 'Valor',
              'type' => 'text',
              'span' => 6,
            ],
            [
              'id' => 'legenda',
              'label' => 'Legenda',
              'type' => 'text',
              'span' => 6,
            ],
          ],
        ],
      ],
    ],
    [
      'id' => 'servico-detalhes',
      'title' => 'Detalhes do serviço',
      'post_type' => 'servicos',
      'context' => 'normal',
      'priority' => 'high',
      'fields' => [
        [
          'id' => 'servico_subtitulo',
          'label' => 'Subtítulo',
          'type' => 'text',
        ],
        [
          'id' => 'servico_link',
          'label' => 'Link do botão',
          'type' => 'url',
        ],
        [
          'id' => 'servico_destaque',
          'label' => 'Destaque na Home',
          'type' => 'checkbox',
        ],
      ],
    ],
    [
      'id' => 'dados-contato',
      'title' => 'Contatos',
      'post_type' => 'dados',
      'context' => 'normal',
      'priority' => 'high',
      'fields' => [
        [
          'id' => 'site_whatsapp',
          'label' => 'WhatsApp com DDD',
          'type' => 'tel',
        ],
        [
          'id' => 'site_telefone',
          'label' => 'Telefone',
          'type' => 'tel',
        ],
        [
          'id' => 'site_email',
          'label' => 'E-mail',
          'type' => 'email',
        ],
        [
          'id' => 'site_endereco',
          'label' => 'Endereço',
          'type' => 'textarea',
        ],
        [
          'id' => 'site_mapa_url',
          'label' => 'Link do mapa',
          'type' => 'url',
        ],
      ],
    ],
  ];
}
