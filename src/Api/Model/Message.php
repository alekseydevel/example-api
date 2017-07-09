<?php
namespace Api\Model;

class Message implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;
    /**
     * @var string
     */
    private $text;
    /**
     * @var string
     */
    private $state;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $sender;

    public function __construct(int $id, string $text, string $state, string $subject, string $sender)
    {
        $this->id = $id;
        $this->text = $text;
        $this->state = $state;
        $this->subject = $subject;
        $this->sender = $sender;
    }

    /**
     * @return int
     */
    public function id(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function text(): string
    {
        return $this->text;
    }

    /**
     * @return string
     */
    public function state(): string
    {
        return $this->state;
    }

    /**
     * @return string
     */
    public function subject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function sender(): string
    {
        return $this->sender;
    }

    /**
     * @param string $state
     */
    public function changeState(string $state)
    {
        $this->state = $state;
    }

    /**
     * @return array
     */
    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id(),
            'text' => $this->text(),
            'sender' => $this->sender(),
            'subject' => $this->subject(),
            'state' => $this->state()
        ];
    }
}
