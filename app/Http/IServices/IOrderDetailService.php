<?php

namespace App\Http\IServices;

use App\Models\OrderDetail;
use Illuminate\Http\Request;

interface IOrderDetailService
{
    public function getAllOrderDetails();

    public function getOrderDetailById(int $id): ?OrderDetail;

    public function createOrderDetail(Request $request): OrderDetail;

    public function updateOrderDetail(int $id, Request $request): bool;

    public function deleteOrderDetail(int $id): bool;
}

