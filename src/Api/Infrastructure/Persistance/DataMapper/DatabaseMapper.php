<?php
declare(strict_types=1);

namespace Api\Infrastructure\Persistance\DataMapper;

use Api\Exception\MessageNotFound;
use Api\Model\Message;
use PDO;

class DatabaseMapper implements DataMapper
{
    const PER_PAGE = 2;

    private $db;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=db;dbname=api;charset=utf8', 'root', 'root'); // for simplicity it`s put here
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function count(): int
    {
        $sql = 'select count(*) from `messages`';
        $query = $this->db->prepare($sql);
        $query->execute();

        return (int) ($query->fetch()[0] / self::PER_PAGE);
    }

    public function fetchFiltered(array $filter, int $page): array
    {
        $params = [];
        $sql = 'select * from `messages`';

        if (isset($filter['state'])) {
            $sql = 'select * from `messages` where state = :state';
            $params = [':state' => $filter['state']];
        }

        $query = $this->db->prepare($sql . $this->appendLimit($page));
        $query->execute($params);

        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    private function appendLimit(int $page): string
    {
        if ($page < 1) {
            $page = 1;
        }

        $page = ($page - 1) * self::PER_PAGE;

        return sprintf(" LIMIT %d OFFSET %d", self::PER_PAGE, $page);
    }

    public function fetchById(int $id): Message
    {
        $query = $this->db->prepare('select * from `messages` where id = :id');
        $query->execute([':id' => $id]);

        $result = $query->fetch(PDO::FETCH_ASSOC);

        if (!$result) {
            throw new MessageNotFound();
        }

        return new Message(
            (int) $result['id'],
            $result['message'],
            $result['state'],
            $result['subject'],
            $result['sender']
        );
    }

    public function save(Message $message): bool
    {
        $sql = $this->db->prepare("update `messages` set state = :state where id = :id");

        return $sql->execute([':state' => $message->state(), ':id' => $message->id()]);
    }
}
