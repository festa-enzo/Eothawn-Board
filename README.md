
<img width="1254" height="1254" alt="logo (2)" src="https://github.com/user-attachments/assets/e6a7bc04-016c-4ed9-a895-bc9f2127d165" />
# 📅 EoThawn Board

O **EoThawn Board** é uma aplicação web para gerenciamento de tarefas semanais, permitindo organizar atividades por dia da semana de forma simples, rápida e intuitiva.

O projeto foi desenvolvido utilizando **PHP (MVC + API REST)** no back-end e **HTML, CSS e JavaScript Vanilla** no front-end.
![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?logo=php)
![JavaScript](https://img.shields.io/badge/JavaScript-ES6-F7DF1E?logo=javascript)
---

## ✨ Funcionalidades

- ✅ Cadastro de usuários
- ✅ Login utilizando JWT
- ✅ Autenticação de rotas
- ✅ Criação de tarefas
- ✅ Edição de tarefas
- ✅ Exclusão de tarefas
- ✅ Arrastar tarefas entre os dias da semana (Drag & Drop)
- ✅ Persistência da movimentação das tarefas
- ✅ Organização por colunas (Segunda a Domingo)
- ✅ Interface moderna e responsiva
- ✅ Proteção de páginas privadas
- ✅ Logout automático quando o token expira

---

# 🛠 Tecnologias

### Front-end

- HTML5
- CSS3
- JavaScript (Vanilla)

### Back-end

- PHP 8
- API REST
- JWT (JSON Web Token)
- Composer
- PDO

### Banco de Dados

- MySQL

### Servidor

- Nginx
- Oracle Linux
- Docker

---

# 📂 Estrutura do Projeto

```text
projeto-JS/
│
├── back-end/
│   ├── App/
│   │   ├── Controllers/
│   │   ├── Core/
│   │   ├── Models/
│   │   └── Repositories/
│   │   
│   │
│   ├── public/
│   ├── config/
│   ├── routes/
│   ├── composer.json
│   └── .env
│
├── front-end/
│   ├── style/
│   ├── images/
│   ├── js/
|   ├── register.html
│   ├── index.html
│   └── login.html
│
├── .gitignore
└── README.md
```

---

# 🔒 Autenticação

A autenticação é realizada através de **JWT**.

Após o login, o token é armazenado no navegador e enviado em todas as requisições protegidas.

Exemplo:

```http
Authorization: Bearer SEU_TOKEN
```

---

# 📌 Endpoints

## Autenticação

| Método | Endpoint | Descrição |
|---------|----------|-----------|
| POST | /api/register | Cadastro |
| POST | /api/login | Login |

## Tarefas

| Método | Endpoint | Descrição |
|---------|----------|-----------|
| GET | /api/tasks | Lista tarefas |
| POST | /api/tasks | Cria tarefa |
| PUT | /api/tasks/{id} | Atualiza tarefa |
| DELETE | /api/tasks/{id} | Exclui tarefa |
| POST | /api/tasks/move | Move tarefa |

---

# ⚙ Instalação

## Clone o projeto

```bash
git clone https://github.com/SEU-USUARIO/eothawn-board.git
```

Entre na pasta

```bash
cd eothawn-board
```

### Executar

```bash
docker compose up --build
```


## 👤 Banco de Dados

O banco é criado automaticamente pelo Docker.

Não é necessário executar scripts SQL manualmente.


---

# 🚀 Próximas melhorias

- [ ] Responsividade para dispositivos móveis
- [ ] Filtro de tarefas
- [ ] Pesquisa por tarefas
- [ ] Categorias
- [ ] Prioridade das tarefas
- [ ] Tema escuro
- [ ] Compartilhamento de boards
- [ ] Recuperação de senha
- [ ] Perfil do usuário

---

# 👨‍💻 Autor

Desenvolvido por **festa-enzo**.
GitHub: https://github.com/festa-enzo
LinkedIn: www.linkedin.com/in/enzo-festa-4935b8308


Projeto criado para estudos de desenvolvimento web utilizando PHP, JavaScript e arquitetura MVC.

---

# 📄 Licença

Este projeto é destinado para fins educacionais.
