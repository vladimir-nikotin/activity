<?php

namespace Vladi\Activity;

class JsonRpcResponse
{
    const JSON_RPC_VERSION = '2.0';

    public function success($result, string $id = null)
    {
        return [
            'jsonrpc' => self::JSON_RPC_VERSION,
            'result' => $result,
            'id' => $id,
        ];
    }

    public function error($error, $number, string $id = null)
    {
        return [
            'jsonrpc' => self::JSON_RPC_VERSION,
            'error' => [
                'message' => $error,
                'code' => empty($number) ? -32000 : $number,
            ],
            'id' => $id,
        ];
    }
}
