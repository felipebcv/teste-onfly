
# 🚀 Onfly Backend Test API

Este sistema foi desenvolvido como teste técnico para a vaga de Backend na empresa **Onfly**.

A API foi construída com **Laravel 11**, utilizando uma arquitetura limpa baseada em **Controllers**, **Services**, **Repositories** e **Interfaces**. A autenticação é feita via **Laravel Sanctum** e a documentação interativa é gerada com **L5-Swagger**.

---

## 🛠 Tecnologias Utilizadas

- PHP 8.2 / Laravel 11  
- MySQL (via Docker)  
- Docker & Docker Compose  
- Laravel Sanctum (autenticação via token)  
- L5-Swagger (documentação interativa)  
- PHPUnit & Mockery (testes unitários e de integração)  

---

## 📂 Estrutura do Código Fonte

### Controllers
`app/Http/Controllers/Api`  
- `AuthController`: Autenticação e geração de tokens  
- `TravelOrderController`: CRUD e gerenciamento de pedidos de viagem

### Requests
`app/Http/Requests`  
- `LoginRequest`  
- `StoreTravelOrderRequest`  
- `UpdateTravelOrderStatusRequest`

### Interfaces
`app/Interfaces`  
- Repositories: `UserRepositoryInterface`, `TravelOrderRepositoryInterface`  
- Services: `AuthServiceInterface`, `TravelOrderServiceInterface`

### Repositories
`app/Repositories`  
- `UserRepository`, `TravelOrderRepository`

### Services
`app/Services`  
- `AuthService`, `TravelOrderService`

### Models
`app/Models`  
- `User`, `TravelOrder`, `Destination`, `TravelOrderStatus`

### Notificações
`app/Notifications`  
- `TravelOrderStatusNotification`

### Outros Arquivos
- `config/l5-swagger.php`: Configuração do Swagger  
- `tests/`: Testes unitários e de integração  
- `Dockerfile` & `docker-compose.yml`: Containerização  
- `entrypoint.sh`: Setup do ambiente Laravel  
- `init.onfly.sql`: Inicialização do banco de dados

---

## ▶️ Como Subir o Sistema com Docker

1. Clone o repositório e acesse a pasta raiz:

   ```bash
   git clone <repo-url>
   cd nome-do-projeto
   ```

2. Suba os containers:

   ```bash
   docker-compose up --build
   ```

- O container **app** compila, instala dependências, roda migrations/seeders (`init.onfly.sql`) e inicia o servidor na porta `8000`.  
- O container **mysql** é iniciado com healthcheck ativo.

---

## 📘 Acessando e Testando com o Swagger

1. Gere a documentação:

   ```bash
   php artisan l5-swagger:generate
   ```

2. Acesse via navegador:  
   [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

3. Clique em **Authorize** e insira seu token:  
   ```text
   Bearer <seu_token>
   ```

4. Para testes, use:  
   - `test@example.com`  
   - `test2@example.com`  
   - **Senha:** `secret`

---

## 📌 Endpoints Principais

### 🔐 Autenticação

- **POST /api/login**  
  Gera um token de acesso.

  **Body:**
  ```json
  { "email": "test@example.com", "password": "secret" }
  ```

  **Response:**
  ```json
  { "token": "1|pPOn8gH3..." }
  ```

---

### ✈️ Pedidos de Viagem

- **POST /api/travel-orders**  
  Cria um novo pedido com status `"requested"`.

  **Body:**
  ```json
  {
    "destination_id": 3,
    "departure_date": "2025-05-01",
    "return_date": "2025-05-10"
  }
  ```

- **PATCH /api/travel-orders/{id}/status**  
  Atualiza o status do pedido (ex: `"approved"` ou `"cancelled"`).  
  ⚠️ *O criador do pedido **não** pode alterar o próprio status.*

  **Body:**
  ```json
  { "status_id": 2 }
  ```

- **PATCH /api/travel-orders/{id}/cancel**  
  Cancela o pedido, se aprovado e com data de retorno futura.

- **GET /api/travel-orders/{id}**  
  Retorna os detalhes de um pedido.

- **GET /api/travel-orders**  
  Lista pedidos, podendo filtrar por `status_id`.

- **GET /api/travel-orders/search**  
  Busca por destino, status e datas de partida.

---

## ⚙️ Funcionalidades do Sistema

- **Autenticação via Sanctum**  
- **Gestão de Pedidos**  
  - Criação com status `"requested"`  
  - Atualização por outro usuário  
  - Cancelamento com validação de data  
  - Consulta individual e busca avançada
- **Notificações**  
  - Por e-mail e registro no banco  
- **Swagger**  
  - Documentação interativa dos endpoints

---

## 🧪 Testes

### Tipos de Testes

- **Unitários:** Componentes isolados (ex: `AuthService`) usando mocks com `Mockery`  
- **Integração:** Testes completos usando banco de dados limpo via `RefreshDatabase`

### Executando os Testes

1. Crie um `.env.testing` com:

   ```env
   APP_ENV=testing
   APP_DEBUG=true
   DB_CONNECTION=mysql
   DB_HOST=mysql
   DB_PORT=3306
   DB_DATABASE=onfly_test
   DB_USERNAME=admin
   DB_PASSWORD=secret
   ```

2. Execute:

   ```bash
   php artisan test
   ```

---

## ✅ Considerações Finais

- Arquitetura limpa e modular  
- Ambiente isolado com Docker  
- Documentação acessível com Swagger  
- Notificações integradas ao fluxo de aprovação/cancelamento  
- Testes confiáveis com PHPUnit + Mockery
