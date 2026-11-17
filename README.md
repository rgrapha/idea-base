# Novanix — guia de uso do tema

> Começando um projeto novo? Leia antes o [NOVO-PROJETO.md](docs/NOVO-PROJETO.md).

Base reutilizável para sites institucionais em WordPress. Mantém o método de trabalho: tipos de conteúdo nativos, campos configurados em PHP, páginas com templates próprios, consultas e componentes reutilizáveis. O visual é intencionalmente neutro; não é uma cópia do site de um cliente.

## 1. O que vem no pacote

- Tema filho de **Hello Elementor** com cabeçalho e rodapé próprios em PHP.
- Sistema próprio de campos, sem dependência de ACF ou do plugin comercial Meta Box.
- Exemplos de Serviços, categorias de serviços, Dados do site e Home.
- Listagem, detalhe, página, busca, taxonomia, página não encontrada e breadcrumb.
- Campos de texto, imagem, galeria, editor, seleção, checkbox e listas repetíveis.
- CSS servido e fonte SCSS correspondente, sem exigir compilação para a primeira instalação.
- Regras para assistentes em `AGENTS.md`, guia de campos e roteiro de entrega.

Os exemplos registram estruturas no administrador, mas não criam posts, páginas, imagens ou menus automaticamente. O tema não faz disparo de formulários, pagamentos, integração com lojas ou publicação automática.

## 2. Instalação em um projeto novo

1. Prepare uma instalação WordPress local ou de homologação com PHP 8.0 ou superior. Esta entrega foi verificada com PHP 8.3; mantenha uma versão suportada pela sua hospedagem e pelo WordPress instalado.
2. Instale o tema pai **Hello Elementor**. A pasta dele deve se chamar `hello-elementor`.
3. Clone o tema a partir do GitHub, conforme a etapa 3 do [NOVO-PROJETO.md](docs/NOVO-PROJETO.md). Não use ZIP ou RAR do tema base.
4. Ative **Novanix**. Não altere o tema de um site de cliente para testar esta base: use outra instalação.
5. Em Páginas, crie Home, escolha o modelo **Home**, salve e recarregue o editor. O grupo Apresentação depende do modelo já salvo.
6. Em Configurações → Leitura, escolha Home como página inicial estática.
7. Crie uma página institucional comum, sem modelo especial. Ela utiliza `page.php`.
8. Em Dados do site, publique **um** cadastro com os contatos. O rodapé usa o primeiro registro publicado por ID; evite duplicatas.
9. Em Serviços, cadastre conteúdo de exemplo próprio, categoria, imagem destacada e detalhes. Marque Destaque na Home para exibi-lo na seção de destaque.
10. Salve Configurações → Links permanentes uma vez após registrar ou renomear tipos e taxonomias. Não execute flush de regras em todas as visitas.
11. Crie o menu e associe à posição **Menu principal**. Inclua Home, as páginas e a URL do arquivo de Serviços.

O plugin Elementor é opcional para esta base. O tema pai é obrigatório. Cabeçalho e rodapé são próprios; este pacote não contém documentos Elementor nem integra automaticamente o Theme Builder. Se optar por esse fluxo, implemente a integração explicitamente e transfira seus documentos separadamente.

## 3. Mapa dos arquivos

| Arquivo/pasta | Responsabilidade |
| --- | --- |
| `style.css` | Identidade do tema e declaração do pai; não é a folha visual principal |
| `functions.php` | Carregamento dos módulos |
| `inc/setup.php` | Suportes do tema, menu, CSS/JS e tamanho da listagem |
| `inc/custom-posts.php` | Registro de tipos de conteúdo e taxonomias |
| `inc/custom-fields.php` | Registro, renderização e salvamento dos grupos de campos |
| `inc/metabox/config.php` | Definição dos grupos, IDs, rótulos e tipos de campo |
| `inc/metabox/renderers.php` | Controles e sanitização por tipo |
| `inc/metabox/admin.js` | Mídia, galerias, máscaras e repetidores no administrador |
| `inc/helpers.php` | Leitura de campos, contatos globais e links de telefone/WhatsApp |
| `templates/page-home.php` | Modelo selecionável Home e consulta de destaques |
| `templates/content-card.php` | Card compartilhado entre consultas |
| `templates/breadcrumb.php` | Navegação: apenas item atual em negrito |
| `page.php` | Página institucional sem modelo específico |
| `single.php` / `single-servicos.php` | Detalhe de publicação / serviço |
| `archive.php` / `archive-servicos.php` | Listagem geral / de serviços |
| `taxonomy.php` | Listagem por termo de uma taxonomia |
| `search.php` / `index.php` | Busca / fallback de listagem |
| `header.php` / `footer.php` | Estrutura HTML, menu e contatos |
| `404.php` | Endereço inexistente |
| `assets/sass/theme.scss` | Fonte dos estilos |
| `assets/css/theme.css` | CSS que o navegador recebe |
| `assets/js/theme.js` | Comportamentos públicos do novo projeto |

Alguns templates especializados incluem o genérico para evitar cópia prematura. Para criar um layout exclusivo de Serviços, substitua a inclusão em `archive-servicos.php` ou `single-servicos.php` por HTML/PHP próprio.

## 4. Como escolher página, post, taxonomia ou campo

- **Página**: conteúdo institucional único, como Sobre e Contato. Campos pertencem à própria página.
- **Custom post type**: coleção de registros, como produtos, lojas, depoimentos ou serviços.
- **Taxonomia**: classificação que o cliente pode ampliar, como categoria, estado ou cidade.
- **Campo**: dado de um registro, como embalagem, subtítulo, telefone ou URL.
- **Repetidor**: lista pequena dentro de um registro, como linha do tempo. Use um post type para coleções grandes, pesquisáveis ou com URLs próprias.
- **Dados do site**: informações globais reutilizadas em várias páginas, sem duplicar contatos em cada template.

Prefira título nativo, editor nativo e imagem destacada para nome, texto principal e foto principal. Use campos extras quando houver uma necessidade real. Defina antes quais rótulos são fixos no código e quais valores o cliente poderá editar.

## 5. Criar um novo cadastro e seu arquivo

Use o registro de `servicos` em `inc/custom-posts.php` como modelo. Para Produtos:

1. Duplique o bloco de `register_post_type` dentro da função de registro.
2. Troque o identificador para `produtos`, rótulos para Produtos/Produto e slug para `produtos`.
3. Mantenha `public`, `has_archive` e `show_in_rest` conforme a finalidade. `supports` habilita título, editor, imagem etc.
4. Crie `archive-produtos.php` e `single-produtos.php` na raiz do tema. Use os arquivos de Serviços como referência.
5. Crie uma taxonomia `categoria_produto` associada a `['produtos']` se o cliente precisar gerenciar categorias.
6. Registre os campos com `'post_type' => 'produtos'` e conecte-os no template.
7. Salve os links permanentes e teste a listagem e um detalhe.

`produtos` é a chave do tipo de conteúdo, não o ID de uma publicação. É essa chave que determina o nome `archive-produtos.php`. O slug da URL pode ser diferente. Evite criar uma Página com o mesmo caminho do arquivo de um post type.

Para categorias hierárquicas use `hierarchical => true`. `show_in_rest => true` permite integração com o editor de blocos. Para um layout exclusivo por categoria use `taxonomy-categoria_produto.php`. O filtro visual não nasce automaticamente do registro da taxonomia: faça a consulta e os controles no front-end.

Renomear uma chave já usada em produção não migra os registros antigos; planeje uma migração. Se os cadastros precisarem sobreviver à troca de tema, mova o registro dos tipos/taxonomias e os campos para um plugin próprio.

## 6. Criar uma página e seus campos

Crie `templates/page-sobre.php` com o padrão:

```php
<?php
/** Template Name: Sobre */
if (!defined('ABSPATH')) {
  exit();
}
get_header();
?>
<main id="conteudo" class="novanix-shell">
  <?php get_template_part('templates/breadcrumb'); ?>
  <?php while (have_posts()): the_post(); ?>
    <h1><?php the_title(); ?></h1>
    <?php the_content(); ?>
    <?php if (has_post_thumbnail()) the_post_thumbnail('large'); ?>
  <?php endwhile; ?>
</main>
<?php get_footer(); ?>
```

Depois crie a página no administrador, escolha o modelo, salve e recarregue. Na configuração de campos use `'template' => 'templates/page-sobre.php'`. O caminho precisa coincidir exatamente. Criar o arquivo não cria a página no banco.

Outra alternativa é `page-sobre.php` na raiz, escolhido automaticamente para uma página com slug `sobre`. Não confunda essa convenção com um modelo selecionável dentro de `templates/`.

## 7. Criar campos e renderizar valores

Consulte [CAMPOS.md](docs/CAMPOS.md) para exemplos completos de grupos, listas, mídia e segurança. O fluxo é:

`config.php → grupo no editor → salvar post_meta → get_post_meta/novanix_meta → HTML do template`

Adicionar um campo ao admin não o exibe automaticamente no site. Os exemplos prontos da Home e do Serviço mostram essa ligação. Um ID deve permanecer estável; trocar o rótulo não perde conteúdo, trocar o ID passa a ler outra chave.

## 8. Consultas, arquivos e singles

Arquivos devem preferir a consulta principal do WordPress, usando `have_posts()`, `the_post()` e `the_posts_pagination()`. Isso preserva a paginação. Em `inc/setup.php`, `pre_get_posts` ajusta somente o arquivo principal de Serviços para oito itens.

Uma vitrine dentro de uma página usa `WP_Query`, como em `templates/page-home.php`: filtra `servico_destaque = 1`, limita quatro posts publicados e chama `wp_reset_postdata()` ao terminar. O checkbox desmarcado vale `'0'`; marcado vale `'1'`.

Para uma consulta secundária paginada, passe `paged` e use `max_num_pages` dessa consulta ao montar os links. Não use `offset` sem considerar seu efeito na paginação. Para filtro público, valide o termo/taxonomia no servidor e evite aceitar argumentos de consulta arbitrários do visitante.

Na single, use o Loop para título/editor/imagem e `novanix_meta()` para os campos simples. Taxonomias são lidas com `get_the_terms()` ou `wp_get_post_terms()`; trate `WP_Error` e retorno vazio.

## 9. Funções úteis incluídas

| Função | Uso |
| --- | --- |
| `novanix_meta($key, $post_id = 0)` | Texto simples de um campo; omitir ID usa o post atual |
| `novanix_site_data()` | Contatos do primeiro cadastro Dados publicado; valores vazios se não existir |
| `novanix_whatsapp_url($number)` | Gera `wa.me`; acrescenta 55 a números brasileiros com DDD de 10/11 dígitos |
| `novanix_phone_url($number)` | Gera `tel:` e preserva o sinal internacional quando informado |
| `novanix_campos_metaboxes()` | Lista declarativa dos grupos; editar a configuração, não chamar no template |
| `novanix_campos_sanitize_field_value()` | Sanitização por tipo usada no salvamento |

Os geradores de telefone não consultam operadora nem verificam se o número possui WhatsApp. Para números internacionais use o código do país; a convenção automática de 55 é brasileira.

Use funções nativas para outras tarefas: `wp_trim_words()` para resumos, `wp_get_attachment_image()` para imagens, `get_post_type_archive_link()` para arquivos, `home_url()` para URLs do site. Evite IDs de páginas e URLs de localhost fixos no código.

## 10. CSS, JavaScript e padrões

O pacote funciona com o CSS já entregue. O SCSS atual contém CSS válido equivalente, sem mixins nem dependências. Pode editar ambos ou compilar com Dart Sass instalado no ambiente: `sass assets/sass/theme.scss assets/css/theme.css --no-source-map`. Nenhum pipeline antigo de Grunt/Ruby foi incluído.

Carregue novos assets por `wp_enqueue_style`/`wp_enqueue_script`, com dependências e `filemtime`, como em `inc/setup.php`. Scripts específicos de páginas devem ser condicionados a `is_page_template()` ou à existência do componente no DOM. Nunca edite só o SCSS esperando que o navegador compile.

No breadcrumb, Início usa a página inicial configurada no WordPress; somente o item atual fica em negrito. Mantenha labels associados aos inputs, foco visível, botões com `type` adequado e imagens com texto alternativo cadastrado na mídia.

## 11. Segurança, formulários e publicação

O salvamento dos campos verifica nonce, permissão e contexto do post; ignora autosave e revisão. Valores passam por sanitização e continuam exigindo escape na saída. O sistema não declara revisão automática dos campos extras: habilitar revisões no post type não garante histórico desses metadados.

Formulários públicos precisam de implementação própria: validação no servidor, nonce quando aplicável, proteção contra spam, limites de envio e configuração de entrega de e-mail. Um botão visual não significa que o formulário envia mensagens. Esta base não inclui um formulário de envio.

Leia [ENTREGA.md](docs/ENTREGA.md). Publicação nunca é automática. Theme ZIP não leva banco, uploads, configurações de Leitura, menus nem documentos Elementor. Configure cada destino novo explicitamente, com backup e validação após o envio.

## 12. Como iniciar o próximo projeto sem explicar tudo novamente

Copie a pasta base, renomeie pasta/Theme Name/Text Domain para o projeto e preserve `Template: hello-elementor`. Leia `AGENTS.md` no começo. Defina páginas, coleções, campos fixos/editáveis, filtros e referência visual. Substitua o exemplo Serviços pelos cadastros necessários e implemente o layout aprovado.

Instrução inicial sugerida para acompanhar o projeto:

> Use o README e o AGENTS.md desta base. Desenvolva localmente, mantendo o padrão de campos e templates. Não publique sem minha autorização. Antes do layout, mapeie as páginas, os tipos de conteúdo, as taxonomias e quais textos devem ser fixos ou editáveis. Use o editor, título e imagem destacada nativos quando forem adequados.

O tema pai e eventuais bibliotecas/plugins possuem suas próprias licenças. Eles não estão redistribuídos neste pacote. Verifique a licença de qualquer imagem, fonte ou biblioteca que adicionar no próximo cliente.

Este tema é distribuído sob a GNU General Public License v2 ou posterior. Veja o texto completo em [LICENSE](LICENSE).
