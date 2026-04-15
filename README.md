# 📚 Shelfie — MongoDB Edition 

Este projeto é o resultado de um **trabalho em grupo** desenvolvido para a disciplina de **Projeto de Banco de Dados** (Curso de ADS - UNINASSAU).

Originalmente concebido em PHP com MySQL (XAMPP), o projeto passou por uma **modernização arquitetural completa**. Minha principal contribuição foi liderar a **migração do banco de dados para MongoDB** e a **containerização da infraestrutura** utilizando Docker e Nginx.

-----

## Meu Papel e Contribuições

Atuei como responsável pela infraestrutura e transição tecnológica:

  * **Migração de Banco de Dados:** Refatoração da camada de persistência, saindo do modelo relacional (MySQL) para documentos flexíveis (**MongoDB**).
  * **Dockerização:** Criação de `Dockerfiles` customizados para PHP 8.2 com as extensões do MongoDB.
  * **Orquestração:** Uso de **Docker Compose** para gerenciar os 3 containers (App, DB, Proxy).
  * **Nginx como Proxy Reverso:** Configuração do servidor web para atuar como porta de entrada e camada de abstração.

-----

## Estrutura do Projeto

```text
shelfie-mongo/
├── docker-compose.yml       ← Orquestra os 3 containers (Web, App, DB)
├── nginx.conf               ← Configuração do servidor web (Proxy Reverso)
├── mongo-init/
│   └── init.js              ← Seed: Cria coleções e índices no MongoDB
└── app/
    ├── Dockerfile           ← Imagem PHP 8.2 + Extensão MongoDB
    ├── conexao.php          ← Lógica de conexão adaptada para NoSQL
    └── [Código-Fonte PHP]   ← Crud da Biblioteca
```

-----

## Modelo de Dados no MongoDB

A migração permitiu uma estrutura mais flexível em comparação ao MySQL original:

**Coleção: `usuarios`**

```json
{
  "_id": ObjectId,
  "nome_usuario": "string",
  "email": "string",
  "senha": "hash bcrypt",
  "criado_em": ISODate
}
```

**Coleção: `livros`**

```json
{
  "_id": ObjectId,
  "usuario_id": "string (ref ao _id do usuário)",
  "titulo": "string",
  "autor": "string",
  "andamento": "Quero Ler | Lendo | Lido | Abandonei",
  "nota": 1-5,
  "favorito": boolean
}
```

-----

## 🔐 Credenciais do Banco (Uso interno entre containers)

| Parâmetro | Valor |
| :--- | :--- |
| **Host** | mongodb |
| **Porta** | 27017 |
| **Banco** | shelfie |
| **Usuário** | shelfie\_user |
| **Senha** | shelfie123 |

-----


