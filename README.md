# XYZ Market

Plataforma web de acompanhamento do mercado financeiro, desenvolvida em PHP 8, MySQL/MariaDB, JavaScript e Bootstrap 5. Reúne cotações de ativos brasileiros e internacionais, radar de variações, painel administrativo e analytics próprios.

> Projeto de acompanhamento informativo. Não executa ordens, não presta consultoria e não constitui recomendação de investimento.

## Funcionalidades

- Dashboard público com visão consolidada dos ativos monitorados.
- Radar de variações e filtros por mercado e ativo.
- Cotações integradas à brapi (Brasil) e Twelve Data (mercados internacionais).
- Cache de cotações no banco de dados.
- Painel administrativo protegido por autenticação.
- Registro básico de eventos de uso para analytics.
- Ativos monitorados configurados em `includes/market.php`.

## Tecnologias e requisitos

- PHP 8.1 ou superior recomendado.
- MySQL 5.7+ ou MariaDB 10.4+.
- Extensões PHP: PDO MySQL, cURL, OpenSSL e JSON.
- Servidor web Apache ou Nginx com HTTPS.

## Instalação

1. Crie um banco de dados MySQL/MariaDB e um usuário com permissões mínimas necessárias.
2. Copie `includes/config.example.php` para `includes/config.php` no servidor.
3. Edite `includes/config.php` com URL, chave aleatória da aplicação, dados do banco e chaves das APIs.
4. Importe `database/install.sql` no banco criado.
5. Publique os arquivos do projeto no document root do domínio.
6. Acesse `/setup.php` uma única vez para cadastrar o primeiro administrador.
7. Remova `setup.php` do servidor imediatamente após a criação do administrador.
8. Acesse `/admin/login.php`.

**Nunca envie `includes/config.php` para o Git.** O arquivo já está listado no `.gitignore`. O arquivo `includes/config.example.php` contém somente valores ilustrativos.

## APIs de mercado

### brapi

Utilizada para os ativos brasileiros e o Ibovespa. Obtenha a credencial em [brapi.dev](https://brapi.dev/) e configure `brapi_token` no arquivo local `includes/config.php`.

### Twelve Data

Utilizada para ações, ETFs, criptoativos e câmbio. Obtenha a chave em [Twelve Data](https://twelvedata.com/) e configure `twelve_data_key` no arquivo local `includes/config.php`.

A disponibilidade de símbolos, índices, atrasos, créditos e permissão de exibição pública depende do plano e dos termos de cada provedor. Consulte a documentação e as condições comerciais antes de colocar o serviço em produção:

- [Documentação brapi](https://brapi.dev/docs)
- [Documentação Twelve Data](https://twelvedata.com/docs)
- [Planos Twelve Data](https://twelvedata.com/pricing)

## Ativos atualmente configurados

A lista é mantida em `includes/market.php`. Além de ações brasileiras e americanas, o projeto acompanha SPY, QQQ e DIA como ETFs de referência para, respectivamente, S&P 500, Nasdaq-100 e Dow Jones Industrial Average. Eles são ETFs negociados em bolsa e **não** são os índices propriamente ditos.

## Segurança e operação

- Gere uma chave longa e aleatória para `app.key`.
- Use HTTPS e senhas exclusivas e fortes.
- Mantenha `includes/config.php` fora do controle de versão.
- Restrinja o acesso web aos diretórios `includes/` e `database/` sempre que possível.
- Remova `setup.php` depois da configuração inicial.
- Faça backups do banco e dos arquivos privados fora do repositório.
- Não exponha tokens das APIs no JavaScript, HTML ou em URLs públicas.
- Ajuste cache e frequência de consulta aos limites do provedor.

## Estrutura

```text
admin/       Painel administrativo e autenticação
api/         Endpoints de mercado e analytics
assets/      CSS e JavaScript
database/    Script SQL de instalação
includes/    Bootstrap, banco, funções e integração de mercado
index.php    Dashboard público
radar.php    Radar de ativos
sobre.php    Página institucional
setup.php    Cadastro inicial do administrador (remover após uso)
```

## Licença

Nenhuma licença de uso ou redistribuição foi concedida neste repositório. Todos os direitos sobre o código permanecem com seus respectivos titulares até que uma licença seja adicionada expressamente.
