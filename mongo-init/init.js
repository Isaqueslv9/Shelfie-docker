// Script de inicialização do banco de dados Shelfie no MongoDB
// Executado automaticamente na primeira vez que o container sobe

db = db.getSiblingDB('shelfie');

// Cria usuário com permissão no banco shelfie
db.createUser({
  user: 'shelfie_user',
  pwd: 'shelfie123',
  roles: [{ role: 'readWrite', db: 'shelfie' }]
});

// Cria coleção de usuários com índices
db.createCollection('usuarios');
db.usuarios.createIndex({ email: 1 }, { unique: true });
db.usuarios.createIndex({ nome_usuario: 1 }, { unique: true });

// Cria coleção de livros com índices
db.createCollection('livros');
db.livros.createIndex({ usuario_id: 1 });
db.livros.createIndex({ usuario_id: 1, andamento: 1 });
db.livros.createIndex({ usuario_id: 1, favorito: 1 });
db.livros.createIndex({ usuario_id: 1, titulo: 'text', autor: 'text' });

print('Banco de dados Shelfie inicializado com sucesso!');
