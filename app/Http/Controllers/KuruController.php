<?php

namespace App\Http\Controllers;

use App\Models\KuruModel;

use Illuminate\Http\Request;
use App\Models\Kuru_logModal;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class KuruController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Retrieve all items from the database (assuming $kurus is a collection)
        $kurus = KuruModel::all();

        return view('kuru', compact('kurus'));
    }

    public function save(Request $request)
    {
        $text = $request->input('list');
        $lists = explode(', ', $text);
        foreach ($lists as $list) {
            $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();

            if ($kuru) {
                $kuru->update(['status' => 'pending']);
            } else {
                // Print a message for debugging
                echo "No record found for list: $list";
            }
        }
        $currentUser = Auth::user();
        $newLog = new Kuru_logModal();
        $newLog->user_id = $currentUser->id;
        $newLog->item_list = $text;
        $newLog->purpose = $request->purpose;
        $newLog->place = $request->place;
        $newLog->status = 'PENDING';
        $newLog->borrow_date = \Carbon\Carbon::parse($request->borrow_date);
        $newLog->due_date = \Carbon\Carbon::parse($request->due_date);
        $newLog->save();
        return redirect('kuru');
    }

}
