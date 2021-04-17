<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class LogsController extends Controller
{
    public function newLog(Request $request)
    {
        $currentUser = Auth::user();
        $newLog = new Log;
        $newLog->user_id = $currentUser->id;
        $newLog->item_list = $request->itemCart;
        $newLog->purpose = $request->purpose;
        $newLog->borrow_date = \Carbon\Carbon::parse($request->borrow_date);
        $newLog->due_date = \Carbon\Carbon::parse($request->due_date);
        $newLog->save();

        $currentUser->line_id = $request->line_id;
        $currentUser->phone = $request->phone;
        $currentUser->save();
        foreach(json_decode($request->itemCart) as $itemID => $quantity) {
            $item = Item::find($itemID); 
            if (!$item->log_list) {
                $item->log_list = json_encode([$newLog->id]);
            } else {
                $item->log_list = json_encode(array_push(่json_decode($item->log_list), $newLog->id));
            }
            $item->save();
        }
    }

    public function cancelLog(Request $request)
    {
        $log = Log::find($request->id);
        $log->status = 'CANCELLED';
        $log->save();
        return 'delete success';
    }

    public function approveLog(Request $request)
    {
        $log = Log::find($request->id);
        if ($log->status == "PENDING") {
            $log->status = 'BORROWED';
        } elseif ($log->status == "BORROWED") {
            $log->status = 'RETURNED';
        }
        $log->save();
    }
}
