<?php

namespace App\Http\Middleware;

use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    public function version(Request $request): ?string
    {
        return vite()->getHash();
    }

    /**
     * Defines the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function share(Request $request): array
    {
        /**
         * @var User|null $userModel
         */
        $userModel = $request->user();
        $user = [];

        if (!is_null($userModel)) {
            $userArray = $userModel->toArray();
            $userArrayData = key_exists('data', $userArray) ? $userArray['data'] : [];

            $user = [
                'nickname' => key_exists('nickname', $userArrayData) ? $userArrayData['nickname'] : null,
                'displayname' => key_exists('displayname', $userArrayData) ? $userArrayData['displayname'] : null,
                'avatar' => key_exists('avatar', $userArrayData) ? $userArrayData['avatar'] : null
            ];
        }

        return array_merge(parent::share($request), [
            'locale' => $request->getPreferredLanguage(),
            'user' => $user
        ]);
    }
}
