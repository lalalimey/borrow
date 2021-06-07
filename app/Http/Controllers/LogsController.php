<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Log;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;
//use Phattarachai\LineNotify\Facade\Line;
use Phattarachai\LineNotify\Line;

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
        $currentUser->year = $request->year;
        $currentUser->nickname = $request->nickname;
        $currentUser->save();
        
        $newLog->log_is_disposable = true;
        foreach(json_decode($request->itemCart) as $itemID => $quantity) {
            $item = Item::find($itemID);
            $newLog->log_is_disposable &= $item->disposable;
            if (!$item->log_list) {
                $item->log_list = json_encode([$newLog->id]);
            } else {
                $array = json_decode($item->log_list);
                $array[] = $newLog->id;
                //return dd(json_encode($array));
                $item->log_list = json_encode($array);
            }
            $item->save();
        }
        if ($newLog->log_is_disposable == true) {
            $newLog->due_date = null;
        }
        $newLog->save();

        //Line::send("มีรายการใหม่\nเข้าสู่ระบบ: https://borrow.docchula.com/");

        $line = new Line('LINE_TOKEN');
        $line->send("มีรายการใหม่\nเข้าสู่ระบบ: https://borrow.docchula.com/");
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
            $log->status = $log->log_is_disposable ? 'RECEIVED' :'BORROWED';

            foreach(json_decode($log->item_list) as $itemID => $quantity) {
                $item = Item::find($itemID);
                $item->quantity -= $quantity;
                $item->save();
            }

        } elseif ($log->status == "BORROWED") {
            $log->status = 'RETURNED';

            foreach(json_decode($log->item_list) as $itemID => $quantity) {
                $item = Item::find($itemID);
                if (!$item->disposable) {
                    $item->quantity += $quantity;
                    
                }
                $item->save();
            }
        }
        $log->save();
    }
}
