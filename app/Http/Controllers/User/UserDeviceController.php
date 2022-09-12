<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Repositories\Device\Interfaces\DeviceRepositoryInterface;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class UserDeviceController extends Controller
{
    /**
     * [GET] user device
     *
     * @param Request $request
     * @return InertiaResponse
     * @link /my/device
     */
    public function device(Request $request): InertiaResponse
    {
        return Inertia::render('user/device', []);
    }

    /**
     * [GET] user device data
     *
     * @param Request $request
     * @param DeviceRepositoryInterface $deviceRepository
     * @return string
     * @link /my/device/data
     */
    public function get(Request $request, DeviceRepositoryInterface $deviceRepository): string
    {
        $user = Auth::user();

        $perPage = $request->get('perPage', 15);
        $columns = ['id', 'ip', 'expired_at', 'created_at', 'updated_at'];
        $devicePaginate = $deviceRepository->paginateByUserId($user->getAttribute('id'), $perPage, $columns);

        return $devicePaginate->toJson();
    }

    /**
     * [DELETE] user device data
     *
     * @param Request $request
     * @param DeviceRepositoryInterface $deviceRepository
     * @return JsonResponse
     * @link /my/device/data
     */
    public function delete(Request $request, DeviceRepositoryInterface $deviceRepository): JsonResponse
    {
        $user = Auth::user();
        $userId = $user->getAttribute('id');

        $ids = $request->query('ids', []);
        $columns = ['id', 'user_id'];
        $devices = $deviceRepository->findByIds($ids, $columns);

        $deleted = [];

        foreach ($devices->all() as $device) {
            /**
             * @var Device $device
             */
            if ($device->getAttribute('user_id') === $userId) {
                $deleted[] = $device->getAttribute('id');
                $device->delete();
            }
        }

        return response()->json([
            'data' => [
                'ids' => $deleted
            ]
        ]);
    }
}
