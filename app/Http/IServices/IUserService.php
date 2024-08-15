<?php

namespace App\Http\IServices;

use Illuminate\Http\Request;
use App\Models\User;

interface IUserService
{
    public function registerUser(Request $request): User;

    public function loginUser(Request $request): string;
}