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
use Phattarachai\LineNotify\Line;
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
    public function index($by)
    {
        // Retrieve all items from the database (assuming $kurus is a collection)
        $kurus = KuruModel::orderBy($by)->get();

        return view('kuru', compact('kurus'));
    }

    public function save(Request $request)
    {
        if ($request->list == null){
            return redirect('kuru')->with('error','please select at least one item');
        }
        $text = $request->input('list');
        $lists = explode(', ', $text);
        if ($request->process == '1'){
            $validator = Validator::make($request->all(), [
                'place' => 'required',
                'purpose' => 'required',
                'start_date' => 'required',
                'due_date' => 'required',
                'phone' => 'required',
                // Add validation rules for other fields
            ]);
            if ($validator->fails()) {
                return redirect('kuru')->with('error','please fill all input box');
            }
            $currentUser = Auth::user();

            $newLog = new Kuru_logModal();
            $newLog->user_id = $currentUser->id;
            $newLog->item_list = $text;
            $newLog->purpose = $request->purpose;
            $newLog->place = $request->place;
            $newLog->status = 'PENDING';
            $newLog->borrow_date = \Carbon\Carbon::parse($request->start_date);
            $newLog->due_date = \Carbon\Carbon::parse($request->due_date);
            $newLog->tel = $request->phone;
            $newLog->save();
            foreach ($lists as $list) {
                $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();
                $kuru->update(['status' => 'pending']);
                $kuru->logid = $newLog->id;
                $kuru->save();
            }
            $line = new Line(env('LINE_NOTIFY_TOKEN_SUPER'));
            $message = 'now borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
            $line->send($message);
            return redirect('kuru/id')->with('success', 'Data saved successfully.');
        } elseif ($request->process == '2'){
            foreach ($lists as $list) {
                $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();

                if ($kuru) {
                    $kuru->update(['status' => 'broken']);
                } else {
                    // Print a message for debugging
                    echo "No record found for list: $list";
                }
            }
        }
    }


    public function approve(Request $request)
    {
        $id = $request->input('id');
        $logid = $request->input('logid');
        $kuru = KuruModel::where('number', $id)->first();
        $kuru->update(['status' => 'borrowed']);
        $kurulogs = Kuru_logModal::where('id',$logid)->get();
        $kurulog = $kurulogs[0];
        $kurulog->status = 'BORROWED';
        $kurulog->save();
        return redirect()->back(); // Redirect back after handling the value
    }

    public function returned(Request $request)
    {
        $id = $request->input('id');
        $logid = $request->input('logid');
        $kuru = KuruModel::where('number', $id)->first();
        $kuru->update(['status' => 'normal']);
        $kuru->update(['logid' => null]);
        $exists = KuruModel::where('logid', $logid)->exists();
        if ($exists) {
            // do nothing
        } else {
            $kurulog = Kuru_logModal::where('id',$logid)->first();
            $kurulog->status = 'COMPLETE';
            $kurulog->save();
        }
        return redirect()->back(); // Redirect back after handling the value
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
        $skippedNumbers = [];
        foreach ($datas as $data){
            $number = $data['number'];
            $count = KuruModel::where('number', $data['number'])->count();
            if ($count > 0) {
                $skippedNumbers[] = $number;
            } else {
                $newkuru = new KuruModel();
                $newkuru->number =$data['number'];
                $newkuru->name =$data['name'];
                $newkuru->division =$data['division'];
                $newkuru->storage =$data['storage'];
                $newkuru->budget =$data['budget'];
                $newkuru->year =$data['year'];
                $newkuru->save();
            }
        }
        if (!empty($skippedNumbers)) {
            // If there are skipped numbers, redirect back with an error message
            return redirect()->back()->with('error', 'Some rows were skipped because they already exist in the database. Skipped Numbers: ' . implode(', ', $skippedNumbers));
        }
        $skippedNumbers = []; // Reset the skipped numbers array
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

    public function savekuru(Request $request)
    {
        // Retrieve form data
        $count = KuruModel::where('number', $request->input('number'))->count();
        if ($count > 0) {
            return redirect()->back()->with('error', 'this id was used.');
        } else {
            $newkuru = new KuruModel();
            $newkuru->number = $request->input('number');
            $newkuru->name = $request->input('name');
            $newkuru->division = $request->input('division');
            $newkuru->storage = $request->input('storage');
            $newkuru->budget = $request->input('budget');
            $newkuru->year = $request->input('year');
            $newkuru->save();
            return redirect()->back()->with('success', 'Data saved successfully.');
        }
    }


}
