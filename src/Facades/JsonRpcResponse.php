<?php

namespace Vladi\Activity\Facades;

use Illuminate\Support\Facades\Facade;

class JsonRpcResponse extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'jsonrpcResponse';
    }
}
