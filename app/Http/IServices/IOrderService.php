<?php 

namespace App\Http\IServices;

use App\Models\Order;
use Illuminate\Http\Request;

interface IOrderService
{
    public function getAllOrders();

    public function getOrderById(int $id): ?Order;

    public function createOrder(Request $request): Order;

    public function updateOrder(int $id, Request $request): bool;

    public function deleteOrder(int $id): bool;
}