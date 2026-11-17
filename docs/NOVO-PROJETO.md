Novo projeto WordPress — etapas antes do tema
Guia para transformar um site em HTML em um site WordPress usando o tema base Novanix. Estas etapas vêm antes do README do tema. Ao terminar a etapa 4, siga o README e o AGENTS.md que estão dentro da pasta do tema.
Repositório do tema base: https://github.com/rgrapha/novanix
O que o assistente pode e não pode fazer
Pode:
•	Criar o banco de dados local, baixar o WordPress e gerar o wp-config.php.
•	Instalar e ativar o plugin Elementor e o tema Hello Elementor.
•	Clonar o tema base, renomear para o projeto e ativar.
•	Criar o .gitignore, ler o site HTML de referência e propor o mapeamento.
•	Converter o HTML para o tema depois que o mapeamento for aprovado.
Não pode:
•	Ativar o Elementor Pro ou lidar com chaves de licença, senhas e dados de acesso. Isso é feito manualmente por quem desenvolve.
•	Versionar ou enviar o núcleo do WordPress, wp-config.php, uploads, plugins ou banco de dados.
•	Publicar em qualquer hospedagem, fazer push ou deploy sem autorização explícita.
•	Alterar o repositório do tema base (novanix) ao trabalhar em um projeto de cliente.
•	Começar a conversão antes de o mapeamento ser aprovado.
Etapa 1 — Ambiente, banco e WordPress
Ambiente local padrão da agência: WampServer.
1.	Confirme que o WampServer está rodando (ícone verde).
2.	Crie a pasta do projeto em C:\wamp64\www\nome-do-projeto.
3.	Crie o banco pelo terminal usando o MySQL do Wamp (no Wamp o padrão costuma ser usuário root sem senha):
C:\wamp64\bin\mysql\mysqlX.X.X\bin\mysql.exe -u root -e "CREATE DATABASE nome_do_projeto CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
4.	Baixe o WordPress em português na pasta do projeto (com WP-CLI):
wp core download --locale=pt_BR
5.	Gere o wp-config.php e instale:
wp config create --dbname=nome_do_projeto --dbuser=root --dbpass="" --dbhost=localhost --locale=pt_BR
wp core install --url=http://localhost/nome-do-projeto --title="Nome do Projeto" --admin_user=USUARIO --admin_email=EMAIL --prompt=admin_password
Sem WP-CLI, baixe o ZIP oficial em wordpress.org, extraia na pasta e conclua a instalação pelo navegador.
Etapa 2 — Elementor e Hello Elementor
wp plugin install elementor --activate
wp theme install hello-elementor
O Elementor Pro é instalado e ativado manualmente no painel, com a licença da agência. A chave nunca vai para o repositório nem para o assistente.
Etapa 3 — Tema base
1.	Clone o tema base na pasta de temas:
cd wp-content/themes
git clone https://github.com/rgrapha/novanix.git nome-do-tema
2.	Desligue o vínculo com o repositório base, para o projeto ter repositório próprio:
cd nome-do-tema
rmdir /s /q .git
3.	Renomeie em style.css o Theme Name e o Text Domain para o projeto. Mantenha Template: hello-elementor.
4.	Ative o tema: wp theme activate nome-do-tema.
Etapa 4 — Git: versionar somente o tema
O repositório do projeto contém apenas o tema. Núcleo do WordPress (wp-admin, wp-includes), wp-config.php, uploads, plugins e banco ficam só no computador.
Opção recomendada: criar o repositório dentro da pasta do tema.
cd wp-content/themes/nome-do-tema
git init
git add .
git commit -m "Início do projeto"
git branch -M main
git remote add origin https://github.com/rgrapha/nome-do-projeto.git
git push -u origin main
O .gitignore do tema já vem no tema base (sistema, editor, node_modules, .map, .zip, .rar, .log, .env).
Alternativa: repositório na raiz do site, com este .gitignore na raiz:
/*
!.gitignore
!wp-content/
wp-content/*
!wp-content/themes/
wp-content/themes/*
!wp-content/themes/nome-do-tema/
Etapa 5 — Publicação (qualquer hospedagem)
Locaweb, HostGator, Hostinger ou outra: o WordPress de produção é instalado pela própria hospedagem. Só o tema é enviado, sempre para:
public_html/wp-content/themes/nome-do-tema
O meio de envio (Git do painel, FTP, gerenciador de arquivos ou ZIP em Aparência → Temas) depende do provedor. O tema não leva banco, páginas, menus, uploads nem configurações do Elementor; isso é configurado no destino, com backup antes. Nunca publicar sem autorização.
Etapa 6 — Converter o site HTML
1.	Coloque o site HTML original em _referencia-html, na raiz do WordPress, fora do tema. Assim ele fica fora do Git e o assistente consegue ler.
2.	Use esta instrução:
Leia o README e o AGENTS.md do tema. O site original em HTML está em _referencia-html. Antes de codar, mapeie as páginas, as seções, o que vira custom post type, taxonomia e campo, e o que fica fixo ou editável. Me mostre esse mapa para eu aprovar. Depois converta usando o padrão do tema, reaproveitando o CSS e as imagens.
3.	Aprove o mapa e siga o README do tema a partir daqui.
