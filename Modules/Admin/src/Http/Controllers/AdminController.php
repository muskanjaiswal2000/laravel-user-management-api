<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Modules\Admin\Services\AdminService;

class AdminController extends Controller
{
    protected $adminService;

    public function __construct(AdminService $adminService)
    {
        $this->adminService = $adminService;
    }

    public function dashboard()
    {
        $stats = $this->adminService->getDashboardStats();

        return response()->json([
            'success' => true,
            'message' => 'Dashboard stats retrieved successfully',
            'data' => $stats
        ], 200);
    }

    public function getAllUsers(Request $request)
    {
        $search = $request->query('search');
        $perPage = $request->query('per_page', 10);

        $users = $this->adminService->getAllUsers($search, $perPage);

        return response()->json([
            'success' => true,
            'message' => 'Users retrieved successfully',
            'data' => $users
        ], 200);
    }

    public function getUserById($id)
    {
        $user = $this->adminService->getUserById($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found',
                'errors' => []
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'User retrieved successfully',
            'data' => $user
        ], 200);
    }
}
