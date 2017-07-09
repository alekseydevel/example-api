<?php
namespace Api\Infrastructure\Serializer;

class JsonSerializer implements Serializable
{
    public function serialize($data)
    {
        return json_encode($data);
    }

    public function unserialize($serialized)
    {
        return json_decode($serialized, true);
    }
}