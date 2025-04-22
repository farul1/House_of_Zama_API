<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\OrderResource;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::query();

        if ($request->has('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        $orders = $query->get();

        return new OrderResource($orders, 'Success', 'List of orders');
    }


    public function store(Request $request)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'catatan' => 'nullable|string',
        ]);

        $order = Order::create($request->all());
        return new OrderResource($order, 'Success', 'Order created successfully');
    }

    public function show($id)
    {
        $order = Order::find($id);
        if ($order) {
            return new OrderResource($order, 'Success', 'Order found');
        } else {
            return new OrderResource(null, 'Failed', 'Order not found');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'client_id' => 'required|exists:clients,id',
            'service_id' => 'required|exists:services,id',
            'catatan' => 'nullable|string',
        ]);

        $order = Order::find($id);
        if ($order) {
            $order->update($request->all());
            return new OrderResource($order, 'Success', 'Order updated successfully');
        } else {
            return new OrderResource(null, 'Failed', 'Order not found');
        }
    }

    public function destroy($id)
    {
        $order = Order::find($id);
        if ($order) {
            $order->delete();
            return new OrderResource($order, 'Success', 'Order deleted successfully');
        } else {
            return new OrderResource(null, 'Failed', 'Order not found');
        }
    }
}
