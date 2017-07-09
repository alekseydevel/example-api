<?php
namespace Api\Command;

use Api\MessageStates;
use PDO;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class InitDb extends Command
{
    private $jsonDataPath;
    private $db;

    public function __construct()
    {
        parent::__construct();
        $this->jsonDataPath = '/app/resources/messages_sample.json'; // hard code for simplicity
        $this->db = new PDO('mysql:host=db;dbname=api;charset=utf8', 'root', 'root');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    protected function configure()
    {
        $this
            ->setName('app:db:init')
            ->setDescription('Creates and fills the DB')
        ;
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln("===== DB init=====");

        $messages = json_decode(
            file_get_contents($this->jsonDataPath),
            true
        );

        if (!isset($messages['messages'])) {
            throw new \InvalidArgumentException("Bad json data");
        }

        $output->writeln(sprintf("messages to import: %d", count($messages['messages'])));
        $output->writeln("creating DB...");

        $this->db
            ->prepare('
            drop table IF EXISTS `messages`;
            CREATE TABLE `messages`
                (
                    id INTEGER,
                    message TEXT,
                    sender VARCHAR (255),
                    subject VARCHAR (255),
                    state CHAR (20),
                    PRIMARY KEY (id)
                );
            ')
            ->execute();

        $output->writeln("importing messages...");

        $i = 0;

        foreach ($messages['messages'] as $message) {
            $state = MessageStates::NOT_READ;
            $sql = $this->db->prepare("insert into messages values(?, ?, ?, ?, ?)");

            $sql->bindParam(1, $message['uid']);
            $sql->bindParam(2, $message['message']);
            $sql->bindParam(3, $message['sender']);
            $sql->bindParam(4, $message['subject']);
            $sql->bindParam(5, $state);
            $sql->execute();

            $i++;
        }

        $output->writeln(sprintf("Messages imported: %d", $i));
        $output->writeln("====== DB init finished ======");
    }
}