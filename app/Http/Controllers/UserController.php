<?php

namespace App\Http\Controllers;

use App\Classes\ApiResponse;
use App\Services\UserService;
use Illuminate\Http\Request;

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
