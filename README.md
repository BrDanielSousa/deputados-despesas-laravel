
# Processo seletivo da empresa RETTA para a vaga de Desenvolvedor PHP/Laravel Junior - REMOTO
**RETTA Tecnologia da Informação**

Este sistema Laravel foi desenvolvido para **sincronizar automaticamente os dados de deputados federais e suas respectivas despesas**, consumindo a [API de Dados Abertos da Câmara dos Deputados](https://dadosabertos.camara.leg.br/). Após a sincronização, os dados são armazenados no banco de dados e exibidos em uma interface amigável, com recursos de filtro, paginação e visualização detalhada.

---

## 🛠️ Tecnologias Utilizadas

- **Laravel** – Framework PHP MVC
- **PHP** – Linguagem backend
- **Blade** – Engine de templates do Laravel
- **PostgreSQL** – Banco de dados relacional
- **Docker** – Contêineres para facilitar o ambiente
- **Bootstrap** – Estilização e responsividade
- **JavaScript/HTML/CSS** – Estrutura das views
- **Queue (Job)** – Para processar sincronizações assíncronas

## 📋 Funcionalidades

### 🔁 Sincronização
- Sincroniza deputados e suas despesas via botão ou rota protegida.
- Utiliza Jobs para processar os dados assíncronamente.
- Armazena os dados em duas tabelas principais: `deputados` e `despesas`.

### 📂 Dashboard
- Lista todos os deputados sincronizados.
- Permite filtro por nome.
- Exibe informações como foto, nome, partido, estado e email.

### 🔍 Detalhes do Deputado
- Mostra todos os dados individuais do deputado.
- Exibe a lista de despesas com:
  - Ano, Mês, Tipo de Despesa, Data, Fornecedor, Valor e Link para o Documento.

---
## ⚙️ Instalação

### Usando Docker (Recomendado)

1. **Clone o repositório**
    ```bash
    git clone https://github.com/BrDanielSousa/deputados-despesas-laravel.git
    cd deputados-despesas-laravel
    ```

2. **Crie o arquivo `.env`**
    ```bash
    cp .env.example .env
    ```

3. **Configure as variáveis de ambiente no `.env`**
    ```
    DB_CONNECTION=pgsql
    DB_HOST=postgres
    DB_PORT=5432
    DB_DATABASE=deputadodespesas
    DB_USERNAME=user
    DB_PASSWORD=password
    ```

4. **Suba os containers Docker**
    ```bash
    docker-compose up -d
    ```

5. **Instale as dependências do PHP**
    ```bash
    docker-compose run --rm composer install
    ```

6. **Gere a chave da aplicação**
    ```bash
    docker-compose run --rm artisan key:generate
    ```

7. **Execute as migrações e seeders**
    ```bash
    docker-compose run --rm artisan migrate --seed
    ```

8. **Crie o link simbólico do storage**
    ```bash
    docker-compose run --rm artisan storage:link
    ```

9. **Ajuste as permissões do storage**
    ```bash
    docker exec -it deputados-despesas-app chown -R www-data:www-data /var/www/storage
    ```

10. **Instale as dependências do Node.js**
    ```bash
    docker-compose run --rm npm install
    ```

11. **Compile os assets**
    ```bash
    docker-compose run --rm npm run build
    ```

---

### Instalação Manual (Sem Docker)

1. **Clone o repositório**
    ```bash
    git clone https://github.com/BrDanielSousa/deputados-despesas-laravel.git
    cd deputados-despesas-laravel
    ```

2. **Crie o arquivo `.env`**
    ```bash
    cp .env.example .env
    ```

3. **Instale as dependências do PHP**
    ```bash
    composer install
    ```

4. **Gere a chave da aplicação**
    ```bash
    php artisan key:generate
    ```

5. **Execute as migrações e seeders**
    ```bash
    php artisan migrate --seed
    ```

6. **Inicie o servidor**
    ```bash
    php artisan serve
    ```
