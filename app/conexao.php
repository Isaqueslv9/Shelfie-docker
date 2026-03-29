<?php
$mongoHost = 'mongodb';
$mongoPort = 27017;
$mongoUser = 'shelfie_user';
$mongoPass = 'shelfie123';
$mongoDb   = 'shelfie';

try {
    $uri = "mongodb://{$mongoUser}:{$mongoPass}@{$mongoHost}:{$mongoPort}/{$mongoDb}";
    $client = new MongoDB\Driver\Manager($uri);

    // Referências às coleções
    $db_livros   = new MongoDB\Driver\BulkWrite();
    $db_usuarios = new MongoDB\Driver\BulkWrite();

    // Helper para executar queries de leitura
    function mongoFind(string $collection, array $filter = [], array $options = []): array {
        global $client, $mongoDb;
        $query  = new MongoDB\Driver\Query($filter, $options);
        $cursor = $client->executeQuery("{$mongoDb}.{$collection}", $query);
        return $cursor->toArray();
    }

    // Helper para contar documentos
    function mongoCount(string $collection, array $filter = []): int {
        global $client, $mongoDb;
        $cmd    = new MongoDB\Driver\Command(['count' => $collection, 'query' => $filter]);
        $result = $client->executeCommand($mongoDb, $cmd)->toArray();
        return (int)($result[0]->n ?? 0);
    }

    // Helper para inserir um documento e retornar o _id gerado
    function mongoInsertOne(string $collection, array $document): MongoDB\BSON\ObjectId {
        global $client, $mongoDb;
        $bulk = new MongoDB\Driver\BulkWrite();
        $id   = $bulk->insert($document);
        $client->executeBulkWrite("{$mongoDb}.{$collection}", $bulk);
        return $id;
    }

    // Helper para atualizar documentos
    function mongoUpdateOne(string $collection, array $filter, array $update): void {
        global $client, $mongoDb;
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->update($filter, ['$set' => $update], ['multi' => false]);
        $client->executeBulkWrite("{$mongoDb}.{$collection}", $bulk);
    }

    // Helper para deletar um documento
    function mongoDeleteOne(string $collection, array $filter): void {
        global $client, $mongoDb;
        $bulk = new MongoDB\Driver\BulkWrite();
        $bulk->delete($filter, ['limit' => 1]);
        $client->executeBulkWrite("{$mongoDb}.{$collection}", $bulk);
    }

    // Helper para executar um comando de agregação
    function mongoAggregate(string $collection, array $pipeline): array {
        global $client, $mongoDb;
        $cmd    = new MongoDB\Driver\Command(['aggregate' => $collection, 'pipeline' => $pipeline, 'cursor' => new stdClass()]);
        $cursor = $client->executeCommand($mongoDb, $cmd);
        return $cursor->toArray();
    }

} catch (MongoDB\Driver\Exception\Exception $e) {
    die("Erro ao conectar com o MongoDB: " . $e->getMessage());
}
?>
