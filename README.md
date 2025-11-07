# Realize Pilates — Sistema de Gestão de Clínica

Sistema web em PHP e MySQL para gestão de clínicas de Pilates.  
Desenvolvido para centralizar **cadastros, agendamentos, pacientes, planos e controle financeiro**, com autenticação segura e estrutura modular escalável.

---

## Funcionalidades Principais

- **Autenticação segura com sessão PHP e senha criptografada**
- **Cadastro de pacientes, instrutores e planos**
- **Agendamentos e controle de aulas**
- **Controle de mensalidades e pagamentos**
- **Painel administrativo com indicadores**
- **Arquitetura modular (config, models, controllers, views)**
- **Configuração via `.env` e integração com banco MySQL**
- **Interface responsiva com Bootstrap 5**

---

## Estrutura do Projeto

```

realize_pilates_final/
├── src/
│ └── config/
│ ├── auth.php # Proteção e autenticação de páginas
│ ├── database.php # Conexão PDO com .env
│ └── app.php # Configurações globais
│
├── public/
│ ├── index.php # Dashboard principal
│ ├── login.php # Tela de login
│ ├── logout.php # Encerramento de sessão
│ ├── includes/ # Header, Navbar e Footer
│ ├── assets/ # CSS, JS e imagens
│ │ ├── css/
│ │ │ ├── theme.css
│ │ │ ├── style.css
│ │ │ └── login.css
│ │ └── js/
│ │ ├── pacientes.js
│ │ └── agendamentos.js
│ └── .htaccess # Regras de acesso e segurança
│
├── database/
│ └── estrutura.sql # Criação completa do banco de dados
│
├── .env # Configurações locais (não versionado)
├── .gitignore
└── README.md

```

---

## Instalação Local (XAMPP)

### Clone o repositório

```bash
git clone https://github.com/seuusuario/realize-pilates.git
cd realize-pilates
```

### Crie o banco de dados

1. Acesse o **phpMyAdmin**
2. Importe o arquivo:

   ```
   database/estrutura.sql
   ```

### Configure o arquivo `.env`

Crie um arquivo `.env` na raiz do projeto com o conteúdo:

```env
DB_HOST=localhost
DB_NAME=clinica_pilates
DB_USER=root
DB_PASS=suasenha
DB_CHARSET=utf8mb4

APP_ENV=development
APP_DEBUG=true
TIMEZONE=America/Sao_Paulo
APP_PATH=/realize_pilates_final/public
```

### Configure o Apache

Verifique se o `DocumentRoot` aponta corretamente para a pasta `/realize_pilates_final/public`.

---

## Acesso Padrão

Após importar o banco, você já tem um **usuário administrador** criado automaticamente:

| Campo       | Valor                      |
| ----------- | -------------------------- |
| **E-mail:** | `admin@clinicapilates.com` |
| **Senha:**  | `admin123`                 |

---

## Estrutura do Banco de Dados

Principais tabelas:

- `usuarios` → Administradores, recepcionistas e instrutores
- `alunos` → Pacientes
- `planos` → Planos de aulas e valores
- `aulas` → Agendamentos e reposições
- `mensalidades` → Controle de pagamentos
- `despesas` → Gastos operacionais

> O arquivo `database/estrutura.sql` contém todas as tabelas e chaves estrangeiras.

---

## Autenticação e Sessão

O sistema utiliza:

- Sessões PHP seguras (`session_regenerate_id(true)`)
- Redirecionamento automático para `login.php` quando não autenticado
- Expiração configurável de sessão por tempo (opcional)

---

## Front-end

- **Bootstrap 5.3.3**
- Estilos próprios: `theme.css`, `style.css`, `login.css`
- Modais e interações JS (`pacientes.js`, `agendamentos.js`)
- Layout limpo e responsivo, inspirado em dashboards administrativos modernos

---

## Tecnologias Utilizadas

| Categoria      | Tecnologia                       |
| -------------- | -------------------------------- |
| Backend        | PHP 8.2 (PDO)                    |
| Banco de Dados | MySQL 8 / MariaDB                |
| Frontend       | HTML5, CSS3, Bootstrap 5         |
| Scripts        | JavaScript (modais e interações) |
| Versionamento  | Git / GitHub                     |
| Servidor local | XAMPP / Apache                   |

---

## Próximos Passos (Roadmap)

- [ ] Módulo financeiro (relatórios de receitas e despesas)
- [ ] Agenda dinâmica semanal (FullCalendar)
- [ ] Upload de fotos dos alunos e planos
- [ ] Relatórios em PDF
- [ ] Integração com e-mail (notificações automáticas)
- [ ] Painel mobile-friendly

---

## Contribuição

1. Faça um **fork** do projeto
2. Crie uma nova branch:

   ```bash
   git checkout -b feature/nome-da-feature
   ```

3. Commit suas alterações:

   ```bash
   git commit -m "Adiciona nova funcionalidade"
   ```

4. Envie para o seu fork:

   ```bash
   git push origin feature/nome-da-feature
   ```

5. Abra um **Pull Request** ✨

---

## Autor

**Desenvolvido por:** [Diogo Wallace](https://github.com/Sr-Ryuk)
_Codenational_
[diogo.wallaceferreira@gmail.com](mailto:diogo.wallaceferreira@gmail.com)
Projeto educacional e profissional para clínicas de Pilates.

---

> “Organização, estética e funcionalidade — o equilíbrio perfeito, dentro e fora do estúdio.”

````

---

## Dica extra
Quando for subir ao GitHub, use o comando:

```bash
git add .
git commit -m "Estrutura inicial do Realize Pilates"
git push -u origin main
````

---
