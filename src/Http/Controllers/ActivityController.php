<?php

namespace Vladi\Activity\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class ActivityController extends Controller
{
    public function handle()
    {
        if (!request()->isJson()) {
            return JsonRpcResponse::error('Parse error', -32700);
        }

        $json = request()->json();
        $id = $json->has('id') ? $json->get('id') : null;

        if (!$json->has('method')) {
            return JsonRpcResponse::error('Invalid request', -32600, $id);
        }

        $method = $json->get('method');
        if (!method_exists($this, $method)) {
            return JsonRpcResponse::error('Invalid request', -32600, $id);
        }

        $params = $json->has('params') ? $json->get('params') : null;

        try {
            $result = $this->{$method}($params);
        } catch (Exception $e) {
            return JsonRpcResponse::error($e->getMessage(), $e->getCode(), $id);
        }

        return JsonRpcResponse::success($result, $id);
    }

    protected function log($params)
    {
        $validator = Validator::make($params, [
            'url' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            throw new Exception('Invalid parameters', -32602);
        }

        DB::table('req_logs')->insert([
            'url' => $params['url'],
        ]);
    }

    protected function get($params)
    {
        $validator = Validator::make($params, [
            'perPage' => 'required|integer|gt:0',
            'page' => 'nullable|integer|gt:0',
        ]);

        if ($validator->fails()) {
            throw new Exception('Invalid parameters', -32602);
        }

        $result = DB::table('req_logs')
            ->select('url', DB::raw('MAX(created_at) as last_visited'), DB::raw('COUNT(*) as visit_count'))
            ->groupBy('url')
            ->orderBy('url')
            ->paginate($params['perPage'], ['*'], 'page', $params['page']);

        return $result;
    }
}
