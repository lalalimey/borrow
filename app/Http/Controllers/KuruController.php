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

    public function findkuru(Request  $request){
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            // Add validation rules for other fields
        ]);
        if ($validator->fails()) {
            return redirect('staff/kuru/find')->with('error','please fill all input box');
        }
        $kuru = KuruModel::where('number', 'like', '%' . $request->number . '%')->first();
        return view('staff.editkuru', compact('kuru'));
    }
    public function deletekuru(Request $request){
        $validator = Validator::make($request->all(), [
            'ole_number' => 'required' ,
        ]);
        $kuru = KuruModel::where('number', $request->old_number)->first();
        $kuru->delete();
        return redirect('staff/kuru/find')->with('success', 'Data deleted successfully.');
    }
    public function editkuru(Request $request){
        $validator = Validator::make($request->all(), [
            'number' => 'required',
            'ole_number' => 'required' ,
            'name' => 'required' ,
            'division' => 'required' ,
            'storage' => 'required' ,
            'budget' => 'required' ,
            'year' => 'required' ,
            'detail' => 'required' ,
        ]);
        $kuru = KuruModel::where('number', $request->old_number)->first();
        $kuru->update(['status' => 'normal']);
        $kuru->update(['number' => $request->number]);
        $kuru->update(['name' => $request->name]);
        $kuru->update(['division' => $request->division]);
        $kuru->update(['storage' => $request->storage]);
        $kuru->update(['budget' => $request->budget]);
        $kuru->update(['year' => $request->year]);
        $kuru->update(['detail' => $request->detail]);
        return redirect('staff/kuru/find')->with('success', 'Data saved successfully.');
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
            $division = [];
            foreach ($lists as $list) {
                $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();
                $kuru->update(['status' => 'pending']);
                array_push($division, $kuru->division);
                $kuru->logid = $newLog->id;
                $kuru->save();
            }
            $division = array_unique($division);
            foreach ($division as $divis){
                if ($divis == "ฝ่ายศิลปวัฒนธรรม"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_ART_AND_CULTURE'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ฝ่ายพัฒน์ ฯ"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_SOCIAL_DEV'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ฝ่ายวิชาการ สพจ."){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_ACADEMIC'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "IT"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_IT'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ฝ่ายถ่ายภาพและสื่อประสม"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_PHOTO'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "วินัยและนิสิตสัมพันธ์"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_STUDENT_RELATION'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ชมรมขับร้องประสานเสียง"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_CHORUS'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ส่งเสริมคุณภาพชีวิต"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_WELFARE'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ชมรมดนตรีฯ"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_MUSIC'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "สพจ." or "Syringe"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_SYRING'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ศานติธรรม"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_SHANTIDHARMA'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ชมรมทำอาหาร"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_COOKING'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "วิเทศสัมพันธ์"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_IR'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }elseif ($divis == "ฝ่ายกีฬาและส่งเสริมสุขภาพ"){
                    $line = new Line(env('LINE_NOTIFY_TOKEN_SPORT'));
                    $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
                    $line->send($message);
                }
            }


            $line = new Line(env('LINE_NOTIFY_TOKEN_SUPER'));
            $message = 'new borrow request please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
            $line->send($message);
            return redirect('kuru/id')->with('success', 'Data saved successfully.');
        } elseif ($request->process == '2'){

            $validator = Validator::make($request->all(), [
                'purpose' => 'required',
                // Add validation rules for other fields
            ]);
            if ($validator->fails()) {
                return redirect('kuru/id')->with('error','please fill all input box');
            }
            $currentUser = Auth::user();

            $newLog = new Kuru_logModal();
            $newLog->user_id = $currentUser->id;
            $newLog->item_list = $text;
            $newLog->purpose = $request->purpose;
            $newLog->place = $request->place;
            $newLog->status = 'BROKEN';
            $newLog->borrow_date = \Carbon\Carbon::parse($request->start_date);
            $newLog->due_date = \Carbon\Carbon::parse($request->due_date);
            $newLog->tel = $request->phone;
            $newLog->save();
            foreach ($lists as $list) {
                $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();
                $kuru->update(['status' => 'broken']);
                $kuru->logid = $newLog->id;
                $kuru->save();
            }
            $line = new Line(env('LINE_NOTIFY_TOKEN2'));
            $message = 'new broken reported please check at https://borrow.docchula.com/staff/kurulogmonitor/'.$newLog->id ;
            $line->send($message);
            return redirect('kuru/id')->with('success', 'Data saved successfully.');
        } elseif ($request->process == '3'){
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
            foreach ($lists as $list) {
                $kuru = KuruModel::where('number', 'like', '%' . $list . '%')->first();
                $kuru->update(['checkup' => 'broken']);
                $kuru->save();
                return redirect('kuru/id')->with('success', 'Data saved successfully.');
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
            if ($data['number']==null){
                return redirect()->back()->with('error', 'กรุณากรอกเลขครุภัณฑ์ให้ครบก่อนอัปโหลด' );
            }
        }
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
                $newkuru->detail = $data['detail'];
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
            $newkuru->detail = $request->input('detail');
            $newkuru->save();
            return redirect()->back()->with('success', 'Data saved successfully.');
        }
    }


}
