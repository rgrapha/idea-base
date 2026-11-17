# Configuração dos campos

## Grupo mínimo

Acrescente um array ao retorno de `novanix_campos_metaboxes()` em `inc/metabox/config.php`:

```php
[
  'id' => 'sobre-apresentacao',
  'title' => 'Apresentação',
  'post_type' => 'page',
  'template' => 'templates/page-sobre.php',
  'context' => 'normal',
  'priority' => 'high',
  'fields' => [
    [
      'id' => 'sobre_titulo',
      'label' => 'Título',
      'type' => 'text',
    ],
    [
      'id' => 'sobre_texto',
      'label' => 'Texto',
      'type' => 'textarea',
    ],
    [
      'id' => 'sobre_url',
      'label' => 'Link do botão',
      'type' => 'url',
    ],
  ],
],
```

Para um post type, use sua chave em `post_type` e omita `template`. `post_type` também aceita array. `template` limita modelos de página e não serve para selecionar archive ou single. Grupos e campos devem ter IDs únicos. Use prefixos por seção.

## Ler e exibir

Dentro do Loop, com `novanix_meta()`:

```php
<?php $text = novanix_meta('sobre_texto'); ?>
<?php if ($text !== ''): ?>
  <p><?php echo nl2br(esc_html($text)); ?></p>
<?php endif; ?>
<?php $url = novanix_meta('sobre_url'); ?>
<?php if ($url !== ''): ?>
  <a href="<?php echo esc_url($url); ?>">Saiba mais</a>
<?php endif; ?>
```

Fora do Loop, passe explicitamente o ID: `novanix_meta('sobre_titulo', $page_id)`. Listas e arrays devem ser lidos com `get_post_meta($page_id, $key, true)`, pois o helper simples só retorna escalares.

Não use `empty()` para ocultar um campo numérico em que zero seja válido. Compare com `''`. Se um campo estiver vazio, oculte também seu rótulo e separador, não apenas o valor.

## Tipos disponíveis

| Tipo | Valor salvo / finalidade |
| --- | --- |
| `text` | Texto de uma linha |
| `textarea` | Texto com quebras, sem HTML |
| `editor` | HTML permitido pelo WordPress; saída com `wp_kses_post()` |
| `url` | URL sanitizada; saída com `esc_url()` |
| `email` / `tel` | E-mail / telefone; o campo não cria automaticamente um link |
| `number` / `range` | String numérica; configuração pode incluir min/max/step |
| `checkbox` | `'1'` marcado ou `'0'` desmarcado |
| `select` / `radio` | Chave entre `options`; somente opções cadastradas são aceitas |
| `color` | Hexadecimal completo, por exemplo `#112233` |
| `date`, `time`, `datetime-local`, `month`, `week`, `search` | Inputs correspondentes; regras de negócio adicionais são do projeto |
| `media` / `image` | Normalmente ID do anexo selecionado; há compatibilidade com URL antiga |
| `gallery` | IDs separados por vírgula; transformar em array para renderizar |
| `repeater` | Array de linhas com subcampos |
| `title` / `separator` | Organização visual do admin; não salvam dados nem precisam de ID |

Aliases legados `upload`, `image_plupload` e `datetime` existem no renderizador por compatibilidade. Prefira os tipos atuais em projetos novos. Não confunda `media` com galeria: seleção múltipla usa `gallery`.

Propriedades comuns: `description`, `placeholder` e `ui => ['width' => '100%']`. Textarea aceita `rows`; editor aceita `rows`, `teeny` e `media_buttons`. Restrições HTML de inputs não substituem validação no servidor para regras como datas futuras e limites de quantidade.

## Títulos fixos e separadores

```php
[
  'type' => 'title',
  'label' => 'Compra direta',
],
[
  'id' => 'canal_direto_texto',
  'label' => 'Texto',
  'type' => 'textarea',
],
[
  'id' => 'canal_direto_url',
  'label' => 'Link do botão',
  'type' => 'url',
],
[
  'type' => 'separator',
],
```

Quando só há um texto por item, use o nome do item como `label` do campo, sem título decorativo ou separador redundante. Títulos fixos do site ficam no HTML; títulos decorativos do admin não são conteúdo do site.

## Select e checkbox

```php
[
  'id' => 'item_formato',
  'label' => 'Formato',
  'type' => 'select',
  'options' => [
    'presencial' => 'Presencial',
    'online' => 'Online',
  ],
],
[
  'id' => 'item_destaque',
  'label' => 'Destaque',
  'type' => 'checkbox',
],
```

Para categorias que o cliente adiciona sozinho, registre uma taxonomia em vez de `select`. Para múltiplos destaques, decida a ordenação na consulta. Um checkbox não impõe exclusividade entre posts.

## Imagem e galeria

Para a imagem principal, habilite `thumbnail` no post type e use a imagem destacada. Para uma imagem extra:

```php
[
  'id' => 'secao_foto',
  'label' => 'Foto da seção',
  'type' => 'image',
  'mime' => 'image',
],
[
  'id' => 'secao_galeria',
  'label' => 'Galeria',
  'type' => 'gallery',
  'max' => 8,
],
```

```php
<?php
$image_id = absint(get_post_meta(get_the_ID(), 'secao_foto', true));
if ($image_id) {
  echo wp_get_attachment_image($image_id, 'large');
}
$ids = array_filter(array_map('absint', explode(',', novanix_meta('secao_galeria'))));
foreach ($ids as $id) {
  echo wp_get_attachment_image($id, 'medium_large');
}
?>
```

`max` na galeria limita a seleção pela interface; se o limite for regra obrigatória, adicione também validação no servidor. O exemplo de renderização usa IDs; dados antigos armazenados como URL precisam de tratamento explícito com `esc_url()`.

Para vídeo, use `media` com `mime => 'video'` e renderize com `wp_video_shortcode(['src' => wp_get_attachment_url($id)])`. Para um provedor externo, armazene uma URL e utilize oEmbed com provedores permitidos. Não imprima iframe arbitrário vindo de texto. O repetidor desta base não possui uploader de mídia aninhado.

## Listas dinâmicas e fixas

```php
[
  'id' => 'historia_itens',
  'label' => 'Linha do tempo',
  'type' => 'repeater',
  'button_add' => 'Adicionar acontecimento',
  'button_del' => 'Remover',
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
```

```php
<?php $items = get_post_meta(get_the_ID(), 'historia_itens', true); ?>
<?php foreach (is_array($items) ? $items : [] as $item): ?>
  <p><strong><?php echo esc_html($item['ano'] ?? ''); ?></strong>
    <?php echo nl2br(esc_html($item['texto'] ?? '')); ?>
  </p>
<?php endforeach; ?>
```

Para exatamente quatro posições, acrescente `'fixed_count' => 4` ao campo repetidor. A interface remove Adicionar/Remover e o salvamento mantém quatro linhas. Nome e descrição podem continuar editáveis. `span` usa uma grade de 12 colunas.

Repetidores suportam texto, textarea e inputs simples, não repetidores aninhados, galerias, editor rico, checkbox ou select completos. Não anuncie esses controles dentro de listas sem implementar o suporte. A ordem segue a sequência das linhas; não há arrastar para ordenar.

## Proteção do conteúdo salvo

O módulo valida nonce e `edit_post`, confere template, ignora autosave/revisão e sanitiza cada tipo. Remove o escape do POST antes de tratar valores e reaplica o escape esperado pela API de metadados; isso evita acumular barras em aspas e perder barras legítimas.

Na saída: texto com `esc_html`, atributos com `esc_attr`, URLs com `esc_url`, textarea com `esc_textarea`, conteúdo de editor com `wp_kses_post`. Não use `stripslashes` indiscriminadamente na renderização. Metadados não são automaticamente registrados para leitura/escrita na API REST; planeje `register_post_meta` com schema, permissões e sanitização se o projeto exigir isso.

## Solução de problemas

- Grupo não aparece: confira `post_type`, caminho de template e se a página foi salva após escolher o modelo.
- Campo não aparece no site: confira leitura do mesmo ID no template, post consultado e cache.
- URL vazia: oculte o botão; não deixe `href=""`.
- Foto ausente: verifique imagem destacada, ID do anexo e se os uploads foram migrados.
- Archive com 404: salve Links permanentes e confira colisão de slug com Página.
- Lista perde conteúdo: confirme nomes dos subcampos, JavaScript do admin e formato array/JSON. Faça backup antes de renomear IDs.
- Dados não reaparecem depois de trocar tema: o banco pode continuar com os registros, mas o novo tema precisa registrar as mesmas chaves.
