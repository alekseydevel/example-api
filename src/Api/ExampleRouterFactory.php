<?php
namespace Api;

use Api\Infrastructure\Persistance\DataMapper\DatabaseMapper;
use Api\Infrastructure\Persistance\Repository\MessageRepository;

class ExampleRouterFactory
{
    public static function create()
    {
        $router = new Router();

        $repository = new MessageRepository(
            new DatabaseMapper()
        );

        // (/)$|^/\?(.*) - with or without query string
        $router->add('(/)$|^/\?(.*)', Endpoints::GET, function (array $params) use ($repository) {

            // this part can be moved to some param validator/converter
            $requestPage = $params['page'] ?? 0;

            $currentPage = $requestPage;
            $nextPage = ++$currentPage;
            $prevPage = $currentPage - 2;

            if ($prevPage < 0) {
                $prevPage = 0;
            }

            /**
             * it`s better to return next/prev url in pagination response part
             */
            return [
                'results' => $repository->fetchAll(
                    [],
                    $currentPage - 1
                ),
                'pagination' => [
                    'next' => $nextPage,
                    'prev' => $prevPage,
                    'totalPages' => $repository->fetchCount()
                ]
            ];
        });

        $router->add('/archived', Endpoints::GET, function () use ($repository) {
            return $repository->fetchArchived();
        });

        $router->add('/([\w]+)', Endpoints::GET, function () use ($repository) {
            $id = func_get_args()[0][1] ?? null;
            return $repository->fetchById((int) $id);
        });

        $router->add('/([\w]+)/read', Endpoints::PATCH, function () use ($repository) {
            $id = func_get_args()[0][1] ?? null;
            $message = $repository->fetchById($id);
            $message->changeState(MessageStates::READ);

            return $repository->save($message);
        });

        $router->add('/([\w]+)/archive', Endpoints::PATCH, function () use ($repository) {
            $id = func_get_args()[0][1] ?? null;
            $message = $repository->fetchById($id);
            $message->changeState(MessageStates::ARCHIVED);

            return $repository->save($message);
        });

        return $router;
    }
}
