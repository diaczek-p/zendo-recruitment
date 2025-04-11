<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChanged;
use App\Http\Requests\OrdersListRequest;
use App\Http\Resources\OrdersListResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrdersController extends Controller
{
    public function index(OrdersListRequest $request)
    {
        $validated = $request->validated();
        $orders = Order::query();

        if (!empty($validated['search'])) {
            $orders->where(function ($query) use ($validated) {
                $query->where('order_number', 'like', '%' . $validated['search'] . '%');
            });
        }

        $perPage = $validated['per_page'] ?? 10;
        $page = $validated['page'] ?? 1;
        $orders = $orders->paginate($perPage, ['*'], 'page', $page);

        return response()->json([
            'data' => OrdersListResource::collection($orders),
            'meta' => [
                'current_page' => $orders->currentPage(),
                'last_page' => $orders->lastPage(),
                'per_page' => $orders->perPage(),
                'total' => $orders->total(),
            ],
        ]);


    }

    public function simulateUpdate()
    {
        $order = Order::inRandomOrder()->first();

        if (!$order) {
            return response()->json(['error' => 'Nie znaleziono zamówień'], 404);
        }

        $statuses = ['pending', 'processing', 'completed', 'cancelled'];
        $currentStatus = $order->status;
        $statuses = array_diff($statuses, [$currentStatus]);
        $randomStatus = $statuses[array_rand($statuses)];
        $order->status = $randomStatus;
        $order->save();

        event(new OrderStatusChanged($order));

        return response()->json([
            'order' => $order,
            'message' => 'Losowa aktualizacja zamówienia została wykonana.',
        ]);
    }

}
