# Fluxo de novos projetos

- Leia README.md e docs/CAMPOS.md antes de alterar a base.
- Este é um tema filho de Hello Elementor; preserve Template: hello-elementor.
- Desenvolva e valide localmente. Publicação exige autorização explícita para cada entrega.
- Configure destino, domínio e autenticação de cada projeto do zero. Nunca reutilize acessos de outro cliente.
- Tema, banco de dados, uploads e documentos Elementor são entregas distintas.
- Use dois espaços e uma propriedade por linha nos arrays de configuração.
- Campos: inc/metabox/config.php. Cadastros e taxonomias: inc/custom-posts.php.
- Título, texto principal e imagem principal usam recursos nativos do WordPress sempre que possível.
- Não duplique categorias em campos de texto: use taxonomias quando editáveis pelo cliente.
- Mantenha valores vazios ocultos e links escapados. Não crie conteúdos fictícios em produção.
- Breadcrumb: Início vai para a Home, somente a página atual fica em negrito.
- Ao mudar estilos, atualize assets/sass/theme.scss e assets/css/theme.css.
- Não adicione credenciais, banco, uploads de cliente, node_modules ou backups ao pacote reutilizável.
