<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class ItemsController extends Controller
{
    public function updateItemInfo(Request $request)
    {

        if ($request->mode == 'add') {
            $newItem = new Item;
            $newItem->name = $request->name;
            $newItem->quantity = $request->quantity;
            $newItem->condition = $request->condition;
            //$newItem->is_available = 1;
            $newItem->save();
        } elseif ($request->mode == 'edit') {
            $item = Item::where('id', '=', $request->itemID)->first(['id']);
            $item->quantity = $request->quantity;
            $item->condition = $request->condition;
            $item->save();
        }
        
    }
}
