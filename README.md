# 📚 Organizador de Atividades

Sistema web desenvolvido em **PHP** e **MySQL** para auxiliar alunos no gerenciamento de atividades escolares, permitindo organizar tarefas, acompanhar prazos, receber notificações e controlar entregas de forma simples e intuitiva.

---

## 🚀 Demonstração

> Em breve...

---

## ✨ Funcionalidades

- 🔐 Sistema de login
- 👤 Controle de usuários
- 📚 Organização por matérias
- 📝 Cadastro de atividades
- 📅 Controle de datas de entrega
- ✅ Marcar atividades como entregues
- 🔎 Filtros de atividades
  - Todas
  - Pendentes
  - Atrasadas
  - Entregues
- 🔔 Sistema de notificações
- 🌙 Tema Claro / Escuro
- 📱 Layout responsivo
- ⚡ Interface moderna utilizando Bootstrap

---

## 📷 Screenshots

### Login

![Login](images/login.png)

### Página Inicial

![Dashboard](images/dashboard.png)

### Notificações

![Notificações](images/notificacoes.png)

### Lista de Atividades

![Atividades](images/atividades.png)

---

## 🛠 Tecnologias Utilizadas

- PHP
- MySQL
- HTML5
- CSS3
- JavaScript
- Bootstrap 4
- Bootstrap Icons
- jQuery

---

## 📂 Estrutura do Projeto

```
organizador-de-atividades/
│
├── css/
├── images/
├── includes/
│   ├── config/
│   ├── filtro.php
│   └── ...
│
├── pages/
│
├── index.php
├── login.php
├── README.md
└── banco.sql
```

---

## ⚙️ Como Executar

### 1. Clone o repositório

```bash
git clone https://github.com/Isaque090/organizador-de-atividades.git
```

### 2. Coloque o projeto no servidor

Exemplo com XAMPP:

```
C:\xampp\htdocs\
```

---

### 3. Crie o banco de dados

Importe o arquivo

```
banco.sql
```

utilizando o phpMyAdmin.

---

### 4. Configure a conexão

Edite o arquivo:

```
includes/config/config.php
```

Exemplo:

```php
$host = "localhost";
$user = "root";
$password = "";
$database = "atividades";
```

---

### 5. Execute

Abra no navegador:

```
http://localhost/organizador-de-atividades
```

---

# 🔔 Sistema de Notificações

O sistema possui notificações para:

- 📚 Nova atividade adicionada
- ✏️ Atividade atualizada
- ⚠️ Atividade atrasada
- 📅 Atividade próxima do vencimento
- 📢 Avisos do sistema

Cada notificação possui:

- Ícone
- Título
- Mensagem
- Data e hora
- Status de leitura

---

# 📋 Controle de Atividades

Cada atividade possui:

- Matéria
- Descrição
- Data de entrega
- Status
- Prazo restante
- Botão para marcar como entregue

---

# 🌙 Temas

O usuário pode alternar entre:

- ☀️ Tema Claro
- 🌙 Tema Escuro

A preferência é salva automaticamente no navegador.

---

# 📌 Funcionalidades Planejadas

- 📊 Dashboard
- 📅 Calendário
- 🔍 Pesquisa de atividades
- ⭐ Prioridade das tarefas
- 📎 Upload de anexos
- 💬 Chat entre alunos e professores
- 📈 Relatórios
- 📤 Exportação para PDF
- 📊 Exportação para Excel
- 🔄 Atualização em tempo real
- 📱 Progressive Web App (PWA)

---

# 🗄 Banco de Dados

O sistema utiliza tabelas como:

- usuários
- matérias
- atividades
- atividades_usuarios
- notificações
- notificações_lidas

---

# 🤝 Contribuindo

Contribuições são bem-vindas!

1. Faça um Fork
2. Crie uma Branch

```bash
git checkout -b minha-feature
```

3. Faça o Commit

```bash
git commit -m "Minha nova funcionalidade"
```

4. Faça o Push

```bash
git push origin minha-feature
```

5. Abra um Pull Request

---

# 📄 Licença

Este projeto foi desenvolvido para fins de estudo e portfólio.

Sinta-se livre para utilizá-lo como referência.

---

## 👨‍💻 Autor

Desenvolvido por **Isaque**.

GitHub:

```
https://github.com/Isaque090
```

---

⭐ Se este projeto foi útil para você, considere deixar uma estrela no repositório.
