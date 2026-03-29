# 📚 Shelfie — MongoDB Edition

Projeto migrado de MySQL para MongoDB, com Docker configurado e pronto para rodar.

---

## 🚀 Como rodar (qualquer PC com Docker instalado)

### 1. Instale o Docker Desktop
- Windows/Mac: https://www.docker.com/products/docker-desktop
- Linux: https://docs.docker.com/engine/install/

### 2. Abra o terminal na pasta do projeto

```bash
cd shelfie-mongo
```

### 3. Suba os containers com um único comando

```bash
docker compose up --build
```

Na primeira vez vai baixar as imagens e instalar a extensão do MongoDB (~2 min).

### 4. Acesse no navegador

```
http://localhost:8080
```

Pronto! 🎉

---

## 🛑 Para parar o projeto

```bash
docker compose down
```

Para parar E apagar todos os dados do banco:

```bash
docker compose down -v
```

---

## 🗂️ Estrutura do projeto

```
shelfie-mongo/
├── docker-compose.yml       ← Orquestra os 3 containers
├── nginx.conf               ← Configuração do servidor web
├── mongo-init/
│   └── init.js              ← Cria coleções e índices no MongoDB
└── app/
    ├── Dockerfile           ← PHP 8.2 + extensão MongoDB
    ├── conexao.php          ← Conexão e helpers do MongoDB
    ├── login.php
    ├── index.php
    ├── meus_livros.php
    ├── adicionar_livro.php
    ├── editar_livro.php
    ├── estatisticas.php
    ├── perfil.php
    ├── processa_exclusao.php
    ├── processa_favorito.php
    ├── logout.php
    ├── css/
    ├── js/
    ├── img/
    └── templates/
```

---

## 🗄️ Modelo de dados no MongoDB

### Coleção: `usuarios`
```json
{
  "_id": ObjectId,
  "nome_usuario": "string",
  "email": "string",
  "senha": "hash bcrypt",
  "criado_em": ISODate
}
```

### Coleção: `livros`
```json
{
  "_id": ObjectId,
  "usuario_id": "string (ref ao _id do usuário)",
  "titulo": "string",
  "autor": "string",
  "editora": "string",
  "categoria": "string",
  "andamento": "Quero Ler | Lendo | Lido | Abandonei",
  "nota": 1-5,
  "resenha": "string",
  "favorito": false,
  "data_adicao": ISODate
}
```

---

## 🔐 Credenciais do banco (apenas uso interno entre containers)

| Parâmetro | Valor         |
|-----------|---------------|
| Host      | mongodb       |
| Porta     | 27017         |
| Banco     | shelfie       |
| Usuário   | shelfie_user  |
| Senha     | shelfie123    |

---

## ❓ Problemas comuns

**Porta 8080 ocupada?**  
No `docker-compose.yml`, troque `"8080:80"` por `"8888:80"` e acesse `http://localhost:8888`.

**Erro de permissão no Linux?**  
Rode com `sudo docker compose up --build`.
