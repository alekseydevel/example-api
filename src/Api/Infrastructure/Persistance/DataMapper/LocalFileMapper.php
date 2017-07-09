<?php
namespace Api\Infrastructure\Persistance\DataMapper;

use Api\Model\Message;
use Api\Exception\MessageNotFound;
use Api\MessageStates;

class LocalFileMapper implements DataMapper
{
    /**
     * @var string
     */
    private $filePath;

    public function __construct(string $filePath)
    {
        $this->filePath = $filePath;
    }

    public function fetchFiltered(array $filter): array
    {
        return array_filter(
            $this->messageArray(),
            function($message) {
                return $message; // ToDo: check state
            }
        );
    }

    public function changeMessageState(int $id, string $state): bool
    {
        $message = $this->fetchById($id);
        $message->changeState($state);
        return true;
    }

    public function save(Message $message): bool
    {
        return true;
    }

    /**
     * @param $id
     * @return Message
     * @throws MessageNotFound
     */
    public function fetchById(int $id): Message
    {
        $messages = $this->messageArray();

        foreach ($messages as $message) {
            if ($message['uid'] == $id) {
                return new Message(
                    $message['uid'],
                    $message['message'],
                    $message['state'] ?? MessageStates::NOT_READ,
                    $message['subject'],
                    $message['sender']
                );
            }
        }

        throw new MessageNotFound();
    }

    private function messageArray()
    {
        // let`s skip check of file existence
        $array = json_decode(
            file_get_contents($this->filePath), true
        );

        return $array['messages'];
    }
}