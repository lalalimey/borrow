<?php

namespace App\Http\Controllers;

use App\Models\KuruModel;
use App\Imports\ExcelImport;
use Illuminate\Http\Request;
use App\Models\Kuru_logModal;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
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


    public function addfromfile(Request $request){
        $validator = Validator::make($request->all(), [
            'excel_file' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }
        $file = $request->file('excel_file');

        // Validate and store the file
        // ...

        // Load the Excel file and let the ExcelImport class handle the import and data storage
        $excelImport = new ExcelImport();
        Excel::import($excelImport, $file);

        // Get the data from the import
        $datas = $excelImport->getData();
        foreach ($datas as $data){
            $newkuru = new KuruModel();
            $newkuru->number =$data['number'];
            $newkuru->name =$data['name'];
            $newkuru->division =$data['division'];
            $newkuru->storage =$data['storage'];
            $newkuru->budget =$data['budget'];
            $newkuru->year =$data['year'];
            $newkuru->save();
        }
        // Now you can process $data as needed

        return redirect()->back()->with('success', 'Excel file uploaded and added to database.');
    }

    public function download()
    {
        $filePath = 'public/template.xlsx'; // Update with your actual file path

        // Ensure the file exists before attempting to download
        if (Storage::exists($filePath)) {
            return Storage::download($filePath, 'template.xlsx');
        } else {
            abort(404);
        }
    }

}
