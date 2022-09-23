<?php

namespace App\Http\Controllers\User;

use App\Repositories\User\Interfaces\UserLogRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserLogController
{
    /**
     * [GET] /my/log
     *
     * @param Request $request
     * @return InertiaResponse
     */
    public function log(Request $request): InertiaResponse
    {
        return Inertia::render('user/log', [

        ]);
    }

    /**
     * [GET] /my/log/data
     *
     * @param Request $request
     * @param UserLogRepositoryInterface $userLogRepository
     * @return string
     */
    public function get(Request $request, UserLogRepositoryInterface $userLogRepository): string
    {
        $user = Auth::user();

        $perPage = $request->get('perPage', 15);
        $devicePaginate = $userLogRepository->paginateByUserId($user->getAttribute('id'), $perPage);

        return $devicePaginate->toJson();
    }
}
