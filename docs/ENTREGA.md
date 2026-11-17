# Roteiro por projeto

## Antes de começar

1. Extraia uma cópia da base para o projeto novo; nunca use a pasta de um cliente como rascunho.
2. Configure WordPress, tema pai e banco próprios.
3. Renomeie identidade e pasta do tema. Mantenha `Template: hello-elementor`.
4. Liste páginas, tipos de conteúdo, taxonomias e campos. Use os mesmos IDs depois de começar a cadastrar conteúdo.
5. Defina título/editor/imagem nativos, rótulos fixos, campos editáveis e listas fixas/dinâmicas.
6. Defina o destino de cada botão e o comportamento quando um campo estiver vazio.
7. Leia e adapte `AGENTS.md` com as regras reais do projeto.

## Validação local

- Ativação sem erro PHP e sem dependências ausentes.
- Home configurada em Leitura e menu associado à posição correta.
- Criar, editar, limpar e salvar cada tipo de campo utilizado.
- Confirmar acentos, aspas, barras legítimas e quebras de linha após reabrir o editor.
- Adicionar/remover repetidores; conferir listas de tamanho fixo.
- Testar permissão com usuário editor e acesso público sem autenticação.
- Verificar campos vazios sem rótulos soltos, botões sem destino ou imagem quebrada.
- Conferir consulta de destaque, arquivo, single, taxonomias, busca e paginação.
- Testar celular, tablet, desktop, teclado, foco, texto alternativo e rolagem horizontal.
- Revisar formulários: envio real, destinatário, spam e mensagem de sucesso/erro.
- Confirmar que alterações de SCSS chegaram ao CSS servido.

## Publicação

Publicar somente após autorização explícita da versão atual. Confirmar domínio, hospedagem, usuário, porta e pasta de destino. Guardar autenticação fora do tema. Fazer backup antes de alterar arquivos ou dados.

| Material | Viaja no ZIP do tema? |
| --- | --- |
| Templates PHP, CSS, JS e configuração dos campos | Sim |
| Posts, páginas, termos e valores dos campos | Não: banco/migração específica |
| Imagens enviadas pelo administrador | Não: uploads e registros de anexos |
| Menus e página inicial | Não: configurações no banco |
| Documentos Elementor e condições de exibição | Não: exportação/importação própria |
| WordPress, Hello Elementor e plugins | Não: instalar separadamente |
| Senhas, chaves privadas e configuração de banco | Nunca incluir |

Não sobrescreva o banco online com uma cópia local sem avaliar o conteúdo que o cliente editou. Para uma correção de template, envie somente o arquivo afetado ou uma versão revisada do tema, preservando os dados. Nunca reutilize os acessos de outro projeto por suposição.

Após publicar, confira o domínio público, imagens, links, ausência de localhost, filtros, formulários, logs e cache. Registre arquivos alterados, backup e pendências de banco. Mantenha disponível a versão anterior para rollback.

## Atualizar a base

Traga melhorias genéricas para uma nova versão da base apenas depois de validá-las. Não copie dados do cliente, fotos, logos, URLs, credenciais ou IDs específicos de widgets. Atualize a versão e a documentação; gere novamente os arquivos RAR e ZIP.

RAR é o pacote de guarda/transporte. ZIP é o pacote instalável. Não inclua `node_modules`, `.git`, caches, backups, SQL, `.env`, uploads de clientes ou ferramentas temporárias de teste no arquivo distribuído.
