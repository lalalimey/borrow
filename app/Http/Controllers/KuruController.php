<?php

namespace App\Http\Controllers;

use App\Models\KuruModel;
use Illuminate\Http\Request;
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

    public function search(Request $request)
    {
        $query = $request->input('query');
        // Perform the search on the Kuru model using the 'name' field as an example.
        // Adjust the search criteria as per your specific use case.
        $kurus = KuruModel::where('name', 'like', '%' . $query . '%')
            ->orWhere('number', $query) // Search by ID
            ->get();


        return view('kuru', compact('kurus'));
    }
    public function save(Request $request){
        $checkedIds = $request->input('checkboxes');
        dd($request);
    }
}
