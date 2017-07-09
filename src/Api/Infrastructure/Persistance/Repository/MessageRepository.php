<?php
namespace Api\Infrastructure\Persistance\Repository;

use Api\Model\Message;
use Api\Infrastructure\Persistance\DataMapper\DataMapper;
use Api\MessageStates;

class MessageRepository
{
    const NO_PAGINATION = 0;

    /**
     * @var DataMapper
     */
    private $dataMapper;

    public function __construct(DataMapper $dataMapper)
    {
        $this->dataMapper = $dataMapper;
    }

    public function fetchCount()
    {
        return $this->dataMapper->count();
    }

    public function fetchAll(array $filter, int $page = self::NO_PAGINATION): array
    {
        return $this->dataMapper->fetchFiltered($filter, $page);
    }

    public function fetchRead(int $page = self::NO_PAGINATION): array
    {
        return $this->dataMapper->fetchFiltered(['state' => MessageStates::READ], $page);
    }

    public function fetchArchived(int $page = self::NO_PAGINATION): array
    {
        return $this->dataMapper->fetchFiltered(['state' => MessageStates::ARCHIVED], $page);
    }

    public function fetchById(int $id): Message
    {
        return $this->dataMapper->fetchById($id);
    }

    public function save(Message $message)
    {
        return $this->dataMapper->save($message);
    }
}
