<?php
namespace Api\Infrastructure\Serializer;

interface Serializable
{
    public function serialize($dataToSerialize);
    public function unserialize($dataToUnserialize);
}