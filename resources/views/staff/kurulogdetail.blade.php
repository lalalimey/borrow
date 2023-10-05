@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h2 class="mb-4">รายการยืมครุภัณฑ์หมายเลข {{$id}}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <?php
                        use App\Models\Kuru_logModal;
                        use App\Models\KuruModel;
                        use App\Models\User;
                        $logs = Kuru_logModal::where('id',$id)->get();
                        $log = $logs[0];
                        $users = User::where('id',$log->user_id)->get();
                        $user = $users[0];
                        ?>
                        <h3 class="mb-4">ข้อมูล</h3>
                        <p>ชื่อผู้ยืม : <span class="">{{$user->name}}</span> </p>
                        <p>เบอร์โทรศัพท์ผู้ยืม : {{$log->tel}}</p>
                        <p>วันที่เริ่มยืม : {{$log->borrow_date}}</p>
                        <p>วันครบกำหนด : {{$log->due_date}}</p>
                        <p>วัตถุประสงค์ :</p>
                        <ul><li>{{$log->purpose}}</li></ul>
                        <p>สถานที่ใช้งาน : {{$log->place}}</p>
                        <p>ช่องทางการติดต่ออื่น (ถ้ามี)</p>
                        <ul>
                            <li>gmail : {{$user->email}}</li>
                            @if($user->line_id != null)
                                <li>line id : {{$user->line_id}}</li>
                            @endif
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h3>รายการการยืม</h3>
                        @php
                            $lists = explode(', ', $log->item_list);
                        @endphp
                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">ID</th>
                                <th scope="col">name</th>
                                <th scope="col">status</th>
                                <th scope="col">ฝ่าย</th>
                                <th scope="col"></th>
                            </tr>
                            </thead>
                            <tbody>

                            @foreach($lists as $item)
                                @php($itemused = KuruModel::where('number',$item)->first())
                                <tr>
                                    <td>{{$itemused->number}}</td>
                                    <td>{{$itemused->name}}</td>
                                    <td>{{$itemused->status}}</td>
                                    <td>{{$itemused->division}}</td>
                                    <td>
                                        @if(Auth::user()->role == 'SUPER')
                                            @if($itemused->status == 'pending')
                                                <a class="btn btn-primary" href="{{route('approve', ['id'=>$itemused->number,'logid'=>$id]) }}">approve</a>
                                            @elseif($itemused->status == 'borrowed')
                                                <a class="btn btn-primary" href="{{route('returned', ['id'=>$itemused->number,'logid'=>$id]) }}">returned</a>
                                            @elseif($itemused->status == 'normal')
                                                <p class="text-success">complete</p>
                                            @endif
                                        @elseif(Auth::user()->role == $itemused->division)
                                            @if($itemused->status == 'pending')
                                                <a class="btn btn-primary" href="{{route('approve', ['id'=>$itemused->number,'logid'=>$id]) }}">approve</a>
                                            @elseif($itemused->status == 'borrowed')
                                                <a class="btn btn-primary" href="{{route('returned', ['id'=>$itemused->number,'logid'=>$id]) }}">returned</a>
                                            @elseif($itemused->status == 'normal')
                                                <p class="text-success">complete</p>
                                            @endif
                                        @else
                                            @if($itemused->status == 'pending')
                                                <button class="btn btn-primary" href="" disabled>approve</button>
                                            @elseif($itemused->status == 'borrowed')
                                                <button class="btn btn-primary" href="" disabled>returned</button>
                                            @elseif($itemused->status == 'normal')
                                                <p class="text-success">complete</p>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                        <a href="/staff/kurulogmonitor" class="btn btn-primary">back</a>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
