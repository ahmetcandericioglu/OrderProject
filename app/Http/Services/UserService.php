<?php

namespace App\Http\Services;

use App\Http\IServices\IUserService;
use App\Models\Order;
use Auth;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use App\Models\User;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UserService implements IUserService
{
    public function registerUser(Request $request): User
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
            ]);

            return User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Registration Error: " . $e->getMessage());
        }
    }

    public function loginUser(Request $request): string
    {
        try {
            $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string',
            ]);
    
            $user = User::where('email', $request->email)->first();
    
            if (! $user || ! Hash::check($request->password, $user->password)) {
                throw ValidationException::withMessages([
                    'email' => ['The provided credentials are incorrect.'],
                ]);
            }
    
            return $user->createToken('API Token')->plainTextToken;
        } catch (ValidationException $e) {
            throw $e;
        } catch (Exception $e) {
            throw new Exception("Login Error: " . $e->getMessage());
        }
    }

    public function logoutUser(): string
    {
        try {
            Auth::user()->currentAccessToken()->delete();
            return "User succeffully logout";
        }
        catch (Exception $e) {
            throw new Exception("Logout Error: ". $e->getMessage());
        }
    }

    public function myOrders(): array
    {
        try {
            $orders = Order::where('user_id', auth()->id())->with('orderDetails')->get();

            if ($orders->isEmpty()) {
                throw new NotFoundHttpException('No orders found for this user.');
            }

            return $orders->toArray();
        } catch (Exception $e) {
            throw new Exception("Getting Order Error: " . $e->getMessage(), 500);
        }
    }
}