@extends('layouts.app')
@section('meta')
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            
                    <div class="d-flex flex-row-reverse">
                        {{-- <button type="button" class="btn btn-primary mx-2" id="buttonCheckout" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" style="pointer-events: none">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                              </svg>
                            <span class="badge bg-light text-dark ms-1" id="badgeItemCount">0</span>
                        </button> --}}
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="itemNameInput" onkeyup="searchItem()" placeholder="ค้นหาด้วยชื่อ">
                        </div>
                    </div>
                    
            
            <table class="table align-middle" id="itemTable">
                <thead>
                    <tr>
                        <th scope="col" class="fit">หมายเลขรายการ</th>
                        <th style="display: none">รายการอุปกรณ์</th>
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
                    $logs = \App\Models\Log::get();
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
                            <td style="display: none">{{$user->name}}</td>
                            <td style="display: none"></td>
                            <td style="display: none">{{$user->line_id}}</td>
                            <td style="display: none">{{$user->phone}}</td>
                            <td class="fit">

                                <button type="button" class="btn btn-outline float-end" id="{{ 'buttonInfoModal' .  $log->id }}" data-bs-toggle="modal" data-bs-target="#infoModal">
                                    รายละเอียด
                                </button>
                            </td>
                            <td class="fit">
                                @if ($log->status == 'PENDING' || $log->status == 'BORROWED')
                                <button type="button" class="btn btn-primary float-end" id="{{ 'buttonApproveModal' .  $log->id }}" data-bs-toggle="modal" data-bs-target="#approveModal">
                                    ยืนยัน
                                </button> 
                                @endif
                                
                            </td>
                        </tr>
                        @php
                            foreach (json_decode($log->item_list) as $itemID => $quantity) {
                                $item = \App\Models\Item::where("id", "=", $itemID)->first();
                                if (!array_key_exists($itemID, $conditionList)) {
                                    $conditionList[$itemID] = [$item->name, $item->condition, $quantity];
                                }
                            }
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
{{-- cancel Modal --}}
<div class="modal fade" id="approveModal" tabindex="-1" aria-labelledby="approveModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approveModalLabel">ยืนยันการทำรายการ</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>หมายเลขรายการที่ต้องการยืนยัน: <span id="logIDtobeApproved"></span></p>
                <p>ต้องการยืนยันรายการใช่หรือไม่</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ไม่ใช่</button>
                <button id="buttonApprove" class="btn btn-primary" data-bs-dismiss="modal">ใช่</button>
            </div>
    </div>
    </div>
</div>
{{-- info Modal --}}
<div class="modal fade" id="infoModal" tabindex="-1" aria-labelledby="infoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="infoModalLabel">รายละเอียด</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table p-2 align-middle" id="checkoutTable">
                    <thead>
                        <tr>
                            <th scope="col">id</th>
                            <th scope="col">ชื่อ</th>
                            <th scope="col">จำนวน</th>
                            <th class="fit"></th>
                        </tr>
                    </thead>
                    <tbody id="infoListBody">
                    </tbody>
                </table>
                {{-- <p>เงื่อนไขการยืม</p> --}}
                {{-- <ul id="conditionsList"></ul> --}}
                <p>ข้อมูลติดต่อ</p>
                <ul id="contactinfo"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
            </div>
    </div>
    </div>
</div>
<p style="display: none" id="JSONitemList">{{ json_encode($conditionList) }}</p>
@endsection

@section('script')
<script>
    let infoButtonList = document.querySelectorAll('[id^="buttonInfoModal"]');
    let itemInfoList = JSON.parse(document.getElementById('JSONitemList').innerText);
    for (let i = 0; i < infoButtonList.length; i++) {
        infoButtonList[i].addEventListener("click", function(event) {
            let buttonID = parseInt(event.target.id.replace('buttonInfoModal',''));
            let itemInfoListforThisLog = JSON.parse(document.querySelectorAll('tr[class="mainList"]')[buttonID - 1].querySelectorAll('td')[1].innerText);
            let innerHTMLofItemList = '';
            let innerHTMLofConditions = '';
            for (const id in itemInfoListforThisLog) {
                
                innerHTMLofItemList += `<tr>
                    <td>${id}</td>
                    <td>${itemInfoList[id][0]}</td>
                    <td>${itemInfoListforThisLog[id]}</td>
                </tr>`;

                innerHTMLofConditions += `<li>${itemInfoList[id][0]}: ${itemInfoList[id][1]}</li>`;
            }
            document.getElementById("infoListBody").innerHTML = innerHTMLofItemList;
            // document.getElementById("conditionsList").innerHTML = innerHTMLofConditions;
            document.getElementById("contactinfo").innerHTML = `
            <li>ชื่อ: ${document.querySelectorAll('tr[class="mainList"]')[buttonID - 1].querySelectorAll('td')[6].innerText}</li>
            <li>ชั้นปีที่: ${document.querySelectorAll('tr[class="mainList"]')[buttonID - 1].querySelectorAll('td')[7].innerText}</li>
            <li>Line ID: ${document.querySelectorAll('tr[class="mainList"]')[buttonID - 1].querySelectorAll('td')[8].innerText}</li>
            <li>เบอร์โทรศัพท์มือถือ: ${document.querySelectorAll('tr[class="mainList"]')[buttonID - 1].querySelectorAll('td')[9].innerText}</li>            
            `;
        });
    };

    // Approve
    const btn = document.getElementById('buttonApprove');
    let logID = 0;
    let approveButtonList = document.querySelectorAll('[id^="buttonApproveModal"]');
    for (let i = 0; i < approveButtonList.length; i++) {
        approveButtonList[i].addEventListener("click", function(event) {
            logID = parseInt(event.target.id.replace('buttonApproveModal',''));
            document.getElementById('logIDtobeApproved').innerText = logID;
        });
    };

    function sendData( data ) {
        const XHR = new XMLHttpRequest();

        let urlEncodedData = "",
            urlEncodedDataPairs = [],
            name;

        // Turn the data object into an array of URL-encoded key/value pairs.
        for( name in data ) {
            urlEncodedDataPairs.push( encodeURIComponent( name ) + '=' + encodeURIComponent( data[name] ) );
        }

        // Combine the pairs into a single string and replace all %-encoded spaces to
        // the '+' character; matches the behavior of browser form submissions.
        urlEncodedData = urlEncodedDataPairs.join( '&' ).replace( /%20/g, '+' );

        // Define what happens on successful data submission
        XHR.addEventListener( "loadend", function(event) {
            location.reload();
        } );

        // Define what happens in case of error
        XHR.addEventListener( 'error', function(event) {
            alert( 'Oops! Something went wrong.' );
        } );

        // Set up our request
        XHR.open( 'POST', '/staff/logmonitor/approve' );

        // Add the required HTTP header for form data POST requests
        XHR.setRequestHeader("x-csrf-token", document.querySelector('meta[name="csrf-token"]').getAttribute('content'));   
        XHR.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );

        // Finally, send our data.
        XHR.send( urlEncodedData );

    }

    btn.addEventListener( 'click', function() {
    sendData( {id: logID} );
    } )

</script>
@endsection