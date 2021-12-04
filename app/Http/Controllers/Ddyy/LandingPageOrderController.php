<?php

namespace App\Http\Controllers\Ddyy;

use App\Http\Common\Result;
use App\Http\Controllers\Controller;
use App\Models\LandingPageOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class LandingPageOrderController extends Controller
{
    public function getOrders(Request $request)
    {
        $size = $request->size;
        $sortBy = $request->sortBy ? $request->sortBy : 'order_date';
        $sortOrder = $request->sortOrder ? $request->sortOrder : 'DESC';
        $product = isset($request->product) ? $request->product : '%';

        $paginator = LandingPageOrder::where('product', 'like', $product)
            ->orderBy($sortBy, $sortOrder)
            ->paginate($size);
        $arr = $paginator->toArray();
        $data = [
            "list" => $arr['data'],
            "totalCount" => $arr['total'],
            "totalPages" => $arr['last_page']
        ];

        $result = new Result();
        $result->successRes($data);
        return $result;
    }

    public function createOrder(Request $request)
    {
        $order = new LandingPageOrder();
        $order->name = $request->name;
        $order->phone = $request->phone;
        $order->address = $request->address;
        $order->product = $request->product;
        $order->message = $request->message;
        $order->status = "CHƯA XỬ LÝ";
        $order->save();

        $result = new Result();
        $result->res("New order '" . $request->product . "' has been created!");
        return response()->json($result, 201);
    }

    public function updateStatus(Request $request)
    {
        DB::enableQueryLog();
        $status = $request->status;
        $idList = $request->idList;
        $affected = LandingPageOrder::whereIn("id", explode(",", $idList))
            ->update(["status" => $status]);

        Log::info(DB::getQueryLog()); // Show results of log
        $result = new Result();
        $result->res("Updated status: '" . $status . "', rows affected: " . $affected);
        return response()->json($result);
    }
}
