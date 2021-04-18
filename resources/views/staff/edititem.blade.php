@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <!-- Button trigger modal -->
            
            <button type="button" id="buttonAddItemModal" class="btn btn-primary float-end" data-bs-toggle="modal" data-bs-target="#addItemModal">
                เพิ่มของ
            </button>
  
            <!-- Modal -->
            <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="addItemModalLabel">เพิ่ม/แก้ไขรายการอุปกรณ์</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <form id="editForm">
                            <div class="modal-body">                      
                                @csrf
                                <div class="mb-3 row">
                                    <label for="name" class="col-sm-2 col-form-label">ชื่อ:</label>
                                    <div class="col-sm-10">
                                        <input id="name" class="form-control" name="name" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="quantity" class="col-sm-2 col-form-label">จำนวน:</label>
                                    <div class="col-sm-4">
                                        <input id="quantity" class="form-control" name="quantity" value="">
                                    </div>
                                    <label for="unit" class="col-sm-2 col-form-label">หน่วย:</label>
                                    <div class="col-sm-4">
                                        <input id="unit" class="form-control" name="unit" value="">
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="disposable" class="col-sm-4 col-form-label" >ใช้แล้วต้องคืนไหม:</label>
                                    <div class="col-sm-8">
                                        <select  class="form-select" name="disposable" id="disposable" required>
                                            <option selected='selected' value="">--เลือก--</option>
                                            <option value="0">ใช่</option>
                                            <option value="1">ไม่</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="owner" class="col-sm-4 col-form-label" >หน่วยงานที่ดูแล:</label>
                                    <div class="col-sm-8">
                                        <select  class="form-select" name="owner" id="owner" required>
                                            <option selected='selected' value="">--เลือก--</option>
                                            <option value="SMCU">สพจ.</option>
                                            <option value="syringe">ฝ่ายพัสดุ</option>
                                          </select>
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label for="conditionTextArea" class="form-label">เงื่อนไขการยืม</label>
                                    <textarea class="form-control" name="condition" id="conditionTextArea" rows="3"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                <input type="submit" class="btn btn-primary" value="บันทึก">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
  
            <table class="table align-middle">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col" id="mainTableHeadName">ชื่อ</th>
                        <th scope="col">จำนวนที่ใช้ได้</th>
                        <th style="display: none" id="mainTableHeadCondition">เงื่อนไข</th>
                        <th class="fit">ใช้แล้วต้องคืนไหม</th>
                        <th class="fit pe-5">หน่วยงานที่ดูแล</th>
                    <th scope="col" class="fit"></th>
                    {{-- <th scope="col" class="fit"></th> --}}
                    </tr>
                </thead>
                @php
                    $items = \App\Models\Item::get();
                @endphp
                <tbody>
                    @foreach($items as $item)
                        <tr>
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }} {{$item->unit}}</td>
                            <td style="display: none">{{ $item->condition ? $item->condition : "ไม่มี" }}</td>
                            <td class="fit">{{ $item->disposable ? 'ไม่' : 'ใช่' }}</td>
                            <td class="fit pe-5">{{ $item->owner == "SMCU" ? 'สพจ.' : 'ฝ่ายพัสดุ' }}</td>
                            <td class="fit">
                                <button type="button" class="btn btn-primary float-end" id="{{ 'editModal' .  $item->id }}" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                    แก้ไข
                                </button>
                            </td>
                            {{-- <td class="fit"><button type="button" class="btn btn-danger">ลบ</button></td> --}}
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
    let mode = null;
    let itemID = null;
    document.getElementById("buttonAddItemModal").addEventListener("click", function() {
        document.getElementById("addItemModalLabel").innerText = "เพิ่มรายการอุปกรณ์";
        mode = 'add';
        document.getElementById("name").value = '';
        document.getElementById("name").readOnly = false;
        document.getElementById("quantity").value = '';
        document.getElementById("conditionTextArea").value = '';
    });

    let editButtonList = document.querySelectorAll('[id^="editModal"]');

    for (let i = 0; i < editButtonList.length; i++) {
        editButtonList[i].addEventListener("click", function(event) {
            document.getElementById("addItemModalLabel").innerText = "แก้ไขรายการอุปกรณ์";
            mode = 'edit';
            itemID = parseInt(event.target.id.replace('editModal',''));
            document.getElementById("name").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[1].innerText;
            document.getElementById("name").readOnly = true ;
            document.getElementById("quantity").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[2].innerText.split(' ')[0];
            document.getElementById("unit").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[2].innerText.split(' ')[1];
            document.getElementById("disposable").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[4].innerText == 'ใช่' ?  0 : 1 ;
            document.getElementById("owner").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[5].innerText == 'สพจ.' ?  'SMCU' : 'syringe' ;
            document.getElementById("conditionTextArea").value = document.querySelectorAll('tr')[itemID].querySelectorAll('td')[3].innerText;
        });
    };

    window.addEventListener( "load", function () {
        function sendData() {
            const XHR = new XMLHttpRequest();

            // Bind the FormData object and the form element
            const FD = new FormData( form );
            FD.append('mode', mode);
            FD.append('itemID', itemID);
            // Define what happens on successful data submission
            XHR.addEventListener( "loadend", function(event) {
                location.reload();
            } );

            // Define what happens in case of error
            XHR.addEventListener( "error", function( event ) {
                alert( 'Oops! Something went wrong.' );
            } );

            // Set up our request
            XHR.open( "POST", "/staff/edit" );

            // The data sent is what the user provided in the form
            XHR.send( FD );

        }

        // Access the form element...
        const form = document.getElementById( "editForm" );

        // ...and take over its submit event.
        form.addEventListener( "submit", function ( event ) {
            event.preventDefault();
            sendData();
        } );
    } );
</script>
@endsection