<?php

namespace App\Http\Controllers\Api;

use App\Classes\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\UserService;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
        
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = $this->userService->findUser($id);
        return ApiResponse::sendResponse($user, 'User retrieved!', 200);
    }
}
