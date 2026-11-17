# Validação da versão 1.0.0

Data: 12/11/2026.

## Executado

- Sintaxe PHP de todos os 21 arquivos PHP: sem erros, PHP 8.3.14.
- Sintaxe dos dois arquivos JavaScript: sem erros.
- Inicialização isolada usando o WordPress local e apontando para a base apenas naquela execução; tema ativo não foi trocado.
- Registro de Serviços, Dados e categoria de serviços.
- Renderização dos três grupos de campos com nonce.
- Renderização dos templates de página, Home, single, arquivo, taxonomia, fallback e 404.
- Sanitização de texto com aspas e barras legítimas.
- Repetidor fixo preenchido até quatro posições.
- Geração de telefone/WhatsApp e retorno vazio quando não há telefone.
- Execução do callback real de salvamento, com todas as escritas de metadados interceptadas em memória: texto, aspas, barras e serialização de repetidores preservados.
- Recusa de salvamento com nonce inválido.
- Verificação do conteúdo distribuído: sem imagens de cliente, banco, uploads, credenciais, caches ou dependências instaladas.

## Limites

Não foi feita ativação na instalação do cliente, importação de conteúdo ou publicação. A base não foi testada em todas as combinações de plugins e versões de PHP. O teste de templates verifica renderização sem erro, não aprovação de um layout final. Instalação, edição no navegador, imagens/galerias, menus e responsividade devem ser conferidos na instalação nova antes da entrega ao próximo cliente, conforme ENTREGA.md.

O visual e os cadastros são exemplos neutros. Nenhum material do site de origem deve ser necessário para utilizar esta base.
