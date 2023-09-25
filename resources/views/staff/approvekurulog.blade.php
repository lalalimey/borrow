@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
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
                            <td class="fit"><a href="/staff/kurulogmonitor/{{$log->id}}">{{ $log->status }}</a></td>
                            <td style="display: none">{{$user->name}} ({{ $user->nickname ? $user->nickname : '' }})</td>
                            <td style="display: none">{{$user->year}}</td>
                            <td class="fit">
                                <button class="btn btn-primary detail-button" data-log-id="{{ $log->id }}">Detail</button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
@section('script')
    <script>
        $(document).ready(function() {
            // Add a click event handler for the detail buttons
            $('.detail-button').on('click', function() {
                // Get the log ID from the custom data attribute
                var logId = $(this).data('log-id');

                // Construct the URL for the detail page
                var detailUrl = '/staff/kurulogmonitor/' + logId;

                // Navigate to the detail page
                window.location.href = detailUrl;
            });
        });
    </script>

@endsection
