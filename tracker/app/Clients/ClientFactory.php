<?php

namespace App\Clients;

use App\Exceptions\ClientException;
use Illuminate\Support\Str;

class ClientFactory
{
    public static function make(string $clientName): Client
    {
        $class = 'App\Clients\\' . Str::studly($clientName);

        if (! class_exists($class)) {
            throw new ClientException('Client not found');
        }

        return new $class;
    }
}
