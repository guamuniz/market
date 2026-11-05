# XYZ Market

**Plataforma web de acompanhamento do mercado financeiro**, criada como projeto independente para demonstrar desenvolvimento full-stack em PHP, integração com APIs externas e construção de ferramentas de análise de dados.

<p align="center">
  <a href="https://market.thebestdigital.xyz/"><strong>Ver demonstração online →</strong></a>
</p>

> O XYZ Market é um projeto informativo e educacional. Não executa ordens, não presta consultoria e não constitui recomendação de investimento.

## Visão geral

O projeto reúne em uma única interface informações de ativos brasileiros e internacionais, com um dashboard de mercado, radar de variações, painel administrativo e analytics próprios. A aplicação consulta provedores externos, normaliza as respostas e utiliza cache em banco de dados para reduzir chamadas repetidas.

## Funcionalidades

- **Dashboard de mercado:** visão consolidada dos ativos monitorados, preços e variações percentuais.
- **Radar de quedas:** filtros por mercado, ativo e faixa de variação negativa.
- **Integração com provedores:** brapi para dados brasileiros e Twelve Data para ativos internacionais, câmbio e criptoativos.
- **ETFs como referências de mercado:** SPY, QQQ e DIA são apresentados como ETFs relacionados aos índices S&P 500, Nasdaq-100 e Dow Jones Industrial Average; não são os próprios índices.
- **Cache de cotações:** armazenamento temporário no MySQL/MariaDB.
- **Painel administrativo:** autenticação e área restrita.
- **Analytics próprio:** registro de eventos e visualizações para acompanhamento básico de uso.

## Tecnologias

| Camada | Tecnologias |
|---|---|
| Backend | PHP 8 |
| Banco de dados | MySQL / MariaDB |
| Frontend | HTML, CSS, JavaScript |
| Interface | Bootstrap 5 |
| Visualização | Chart.js |
| Integrações | brapi e Twelve Data |
| Infraestrutura | Servidor web com HTTPS |

## Demonstração

Acesse a versão publicada:

**[https://market.thebestdigital.xyz/](https://market.thebestdigital.xyz/)**

A demonstração depende da disponibilidade dos provedores de dados e das permissões de cada plano de API. Cotações podem ter atraso ou indisponibilidade.

## Estrutura do projeto

```text
admin/       Painel administrativo e autenticação
api/         Endpoints de mercado e analytics
assets/      CSS e JavaScript
database/    Script SQL de instalação
includes/    Configuração, banco, funções e integração de mercado
index.php    Dashboard público
radar.php    Radar de variações
sobre.php    Página institucional
setup.php    Cadastro inicial do administrador
```

## Instalação

### Requisitos

- PHP 8.0 ou superior (PHP 8.1+ recomendado).
- MySQL 5.7+ ou MariaDB 10.4+.
- Extensões PHP: PDO MySQL, cURL, OpenSSL e JSON.
- Apache ou Nginx com HTTPS.

### Passos

1. Crie um banco MySQL/MariaDB e um usuário com permissões mínimas necessárias.
2. Copie `includes/config.example.php` para `includes/config.php` no servidor.
3. Configure URL, chave aleatória da aplicação, dados do banco e chaves das APIs em `includes/config.php`.
4. Importe `database/install.sql`.
5. Publique os arquivos no document root do domínio.
6. Acesse `/setup.php` uma única vez para criar o primeiro administrador.
7. **Remova `setup.php` do servidor imediatamente após a criação do administrador.**
8. Acesse `/admin/login.php`.

## Configuração das APIs

- **brapi:** [brapi.dev](https://brapi.dev/) — configure `brapi_token`.
- **Twelve Data:** [twelvedata.com](https://twelvedata.com/) — configure `twelve_data_key`.

Consulte a [documentação da brapi](https://brapi.dev/docs), a [documentação da Twelve Data](https://twelvedata.com/docs) e os [planos e condições da Twelve Data](https://twelvedata.com/pricing). Cobertura, limites, atrasos e direitos de exibição pública variam por provedor, instrumento e plano.

## Segurança

- **Nunca** faça commit do arquivo `includes/config.php`, de tokens, senhas ou dados reais de conexão.
- O arquivo `includes/config.php` está listado no `.gitignore`; mantenha apenas `config.example.php` no repositório.
- Gere uma chave aleatória longa para `app.key`.
- Use HTTPS, senhas fortes e um usuário de banco com privilégios mínimos.
- Restrinja o acesso web aos diretórios internos, como `includes/` e `database/`, sempre que possível.
- Remova `setup.php` depois da configuração inicial.
- Não exponha chaves de API no JavaScript, HTML ou em URLs públicas.

## Escopo e limitações

O XYZ Market é um projeto de monitoramento e visualização. Não realiza operações financeiras nem garante detecção de oportunidades. A qualidade, atualização e disponibilidade dos dados dependem dos provedores, dos planos contratados e das respectivas regras de licenciamento. Indicadores técnicos que dependam de histórico extenso não são apresentados como funcionalidades concluídas nesta versão.

## Licença

Este repositório não concede, por enquanto, licença aberta de uso, cópia, modificação ou redistribuição. Todos os direitos permanecem reservados ao titular do projeto, salvo autorização expressa.
