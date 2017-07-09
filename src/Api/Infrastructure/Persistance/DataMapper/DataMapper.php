<?php
namespace Api\Infrastructure\Persistance\DataMapper;

use Api\Model\Message;

interface DataMapper
{
    public function count(): int;
    public function fetchFiltered(array $filter, int $page): array;
    public function fetchById(int $id): Message;
    public function save(Message $message): bool;
}
