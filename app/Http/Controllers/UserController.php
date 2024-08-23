<?php

namespace App\Http\Controllers;

use App\Http\IServices\IUserService;
use App\Http\Services\UserService;
use Illuminate\Validation\ValidationException;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserController extends Controller
{
    protected $userService;

    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    public function register(Request $request)
    {
        try {
            $user = $this->userService->registerUser($request);
            return response()->json($user, 201);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $token = $this->userService->loginUser($request);
            return response()->json(['token' => $token], 200);
        } catch (AuthenticationException $e) {
            return response()->json(['error' => 'Unauthorized'], 401);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function logout(Request $request){ 
        try { 
            $logout = $this->userService->logoutUser();
            return response()->json([ 'message' => $logout], 200);   
        }
        catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function myOrders(){
        try {
            $orders = $this->userService->myOrders();
            return response()->json(['orders' => $orders], 200);
        } catch (NotFoundHttpException $e) {
            return response()->json(['error'=> $e->getMessage()], 400);
        } catch (Exception $e) {
            return response()->json(['error'=> $e->getMessage()], 500);
        }
    }
}
