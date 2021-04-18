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
            $newItem->unit = $request->unit;
            $newItem->disposable = $request->disposable;
            $newItem->owner = $request->owner;
            $newItem->condition = $request->condition;
            $newItem->save();
        } elseif ($request->mode == 'edit') {
            $item = Item::where('id', '=', $request->itemID)->first(['id']);
            $item->quantity = $request->quantity;
            $item->condition = $request->condition;
            $item->unit = $request->unit;
            $item->disposable = $request->disposable;
            $item->owner = $request->owner;
            $item->save();
        }
        
    }
}
