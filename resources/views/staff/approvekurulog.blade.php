@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="d-flex flex-row-reverse">
                    <div class="col-xs-3">
                        <input type="text" class="form-control" id="itemNameInput" onkeyup="searchItem()" placeholder="ค้นหาด้วยชื่อ">
                    </div>
                </div>
                <h2>การยืมครุภัณฑ์</h2>
                <table class="table align-middle" id="itemTable">
                    <thead>
                    <tr>
                        <th scope="col" class="fit">หมายเลขรายการ</th>
                        <th scope="col">จุดประสงค์</th>
                        <th scope="col" class="fit">วันที่ยืม</th>
                        <th scope="col" class="fit">วันที่คืน</th>
                        <th scope="col" class="fit">สถานะ</th>
                        <th style="display: none">ชื่อผู้ยืม</th>
                        <th style="display: none">ชั้นปี</th>
                        <th style="display: none">ID Line</th>
                        <th style="display: none">เบอร์โทรศัพท์มือถือ</th>
                        <th scope="col" class="fit"></th>
                        <th scope="col" class="fit"></th>
                    </tr>
                    </thead>
                    @php
                        $conditionList = array();
                        $logs = \App\Models\Kuru_logModal::get();
                    @endphp
                    <tbody>
                    @foreach($logs as $log)
                        @php
                            $user = \App\Models\User::find($log->user_id)
                        @endphp
                        <tr class="mainList" data-bs-toggle="collapse" data-bs-target="#collapseExample" aria-expanded="false" aria-controls="collapseExample">
                            <td class="fit">{{ $log->id }}</td>
                            <td style="display: none" id="JSONlogItemList">{{ $log->item_list }}</td>
                            <td scope="col">{{ $log->purpose }}</td>
                            <td class="fit">{{ $log->borrow_date }}</td>
                            <td class="fit">{{ $log->due_date }}</td>
                            <td class="fit">{{ $log->status }}</td>
                            <td style="display: none">{{$user->name}} ({{ $user->nickname ? $user->nickname : '' }})</td>
                            <td style="display: none">{{$user->year}}</td>
                            <td style="display: none">{{$user->line_id}}</td>
                            <td style="display: none">{{$user->phone}}</td>
{{--                            <td class="fit">--}}

{{--                                <button type="button" class="btn btn-outline float-end" id="{{ 'buttonInfoModal' .  $log->id }}" data-bs-toggle="modal" data-bs-target="#infoModal">--}}
{{--                                    รายละเอียด--}}
{{--                                </button>--}}
{{--                            </td>--}}
{{--                            <td class="fit">--}}
{{--                                @if ($log->status == 'PENDING' || $log->status == 'BORROWED')--}}
{{--                                    <button type="button" class="btn btn-primary float-end" id="{{ 'buttonApproveModal' .  $log->id }}" data-bs-toggle="modal" data-bs-target="#approveModal">--}}
{{--                                        ยืนยัน--}}
{{--                                    </button>--}}
{{--                                @endif--}}

{{--                            </td>--}}
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
