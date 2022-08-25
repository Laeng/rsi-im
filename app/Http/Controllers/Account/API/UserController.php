<?php

namespace App\Http\Controllers\Account\API;

use Illuminate\Http\Request;

class UserController
{
    public function data(Request $request): array
    {
        $user = $request->user();
        $data = $user->getAttribute('data');

        $result = [];

        if ($request->user()->tokenCan('basic-info')) {
            $result = [
                'id' => $data['account_id'],
                'name' => $data['nickname'],
                'nickname' => $data['nickname'],
                'display_name' => $data['display_name'],
                'avatar' => $data['avatar'],
                'badges' => $data['badges']
            ];
        }

        if ($request->user()->tokenCan('list-of-organizations')) {
            $result['organizations'] = $data['organizations'];
        }

        if ($request->user()->tokenCan('list-of-games')) {
            $result['games'] = $data['games'];
        }

        if ($request->user()->tokenCan('launch-game')) {
            $result['launcher'] = [
                'username' => $data['username'],
                'session_id' => $data['session_id'],
                'session_data' => $data['session_data'],
                'releases' => $data['releases']
            ];
        }

        return $result;
    }
}
