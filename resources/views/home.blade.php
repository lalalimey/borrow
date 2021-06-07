@extends('layouts.app')

@if (Auth::user()->agreetotermsandconditions && Auth::user()->dataconsent)
@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            
                    <div class="d-flex flex-row-reverse">
                        <button type="button" class="btn btn-primary mx-2" id="buttonCheckout" data-bs-toggle="modal" data-bs-target="#checkoutModal">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-cart" viewBox="0 0 16 16" style="pointer-events: none">
                                <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                              </svg>
                            <span class="badge bg-light text-dark ms-1" id="badgeItemCount">0</span>
                        </button>
                        <div class="col-xs-3">
                            <input type="text" class="form-control" id="itemNameInput" onkeyup="searchItem()" placeholder="ค้นหาด้วยชื่อ">
                        </div>
                    </div>
                    
            
            <table class="table align-middle" id="itemTable">
                <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col" id="mainTableHeadName">ชื่อ</th>
                        <th scope="col">จำนวนที่ใช้ได้</th>
                        <th style="display: none" id="mainTableHeadCondition">เงื่อนไข</th>
                        <th class="fit">ใช้แล้วต้องคืนไหม</th>
                        <th class="fit pe-5">หน่วยงานที่ดูแล</th>
                        <th class="fit"></th>
                    </tr>
                </thead>
                @php
                    $items = \App\Models\Item::get();
                @endphp
                <tbody>
                    @foreach($items as $item)
                        <tr class="mainList">
                            <td>{{ $item->id }}</td>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->quantity }} {{$item->unit}}</td>
                            <td style="display: none">{{ $item->condition ? $item->condition : "ไม่มี" }}</td>
                            <td class="fit">{{ $item->disposable ? 'ไม่' : 'ใช่' }}</td>
                            <td class="fit pe-5">@php
                                switch ($item->owner) {
                                case "SMCU":
                                    echo "สพจ.";
                                    break;
                                case "syriinge":
                                    echo "ฝ่ายพัสดุ";
                                    break;
                                case "photo":
                                    echo "ฝ่าย Photo";
                                    break;
                            }
                            @endphp</td>
                            <td class="fit">
                                <button type="button" class="btn btn-outline-success" id="{{ 'buttonAddToCartModal' .  $item->id }}" data-bs-toggle="modal">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus-circle" viewBox="0 0 16 16" style="pointer-events: none">
                                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                                        <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4z"/>
                                    </svg>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
<!-- Checkout Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="checkoutModalLabel">สรุปรายการอุปกรณ์</h5>
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
                    <tbody id="checkoutListBody">
                        
                    </tbody>
                </table>
                <p>เงื่อนไขการยืม</p>
                <ul id="conditionsList"></ul>
                <form id="checkoutForm">                    
                    @csrf
                    <div class="mb-3">
                        <label for="purposeTextArea" class="form-label">จุดประสงค์:</label>
                        <textarea class="form-control" name="purpose" id="purposeTextArea" rows="3" value="" required></textarea>
                    </div>
                    <div class="mb-3 row">
                        <label for="start_date" class="col-sm-4 col-form-label">วันที่ยืม:</label>
                        <div class="col-sm-8">
                            <input id="start_date" class="form-control" name="start_date" value="" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="due_date" class="col-sm-4 col-form-label">วันที่คืน:</label>
                        <div class="col-sm-8">
                            <input id="due_date" class="form-control" name="due_date" value="">
                        </div>
                    </div>
                    <h6 class="pt-1">ข้อมูลสำหรับการติดต่อ</h6>
                    <div class="mb-3 row">
                        <label for="nickname" class="col-sm-4 col-form-label">ชื่อเล่น:</label>
                        <div class="col-sm-8">
                            <input id="nickname" class="form-control" name="nickname" value="{{ Auth::user()->nickname ? Auth::user()->nickname : '' }}">
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="year" class="col-sm-4 col-form-label" >ชั้นปีที่:</label>
                        <div class="col-sm-8">
                            <select  class="form-select" name="year" id="year" required>
                                <option selected='selected' value="">--เลือก--</option>
                                <option value="1">1 (MDCU77)</option>
                                <option value="2">2 (MDCU76)</option>
                                <option value="3">3 (MDCU75)</option>
                                <option value="4">4 (MDCU74)</option>
                                <option value="5">5 (MDCU73)</option>
                                <option value="6">6 (MDCU72)</option>
                              </select>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="phone" class="col-sm-4 col-form-label">เบอร์โทรศัพท์มือถือ:</label>
                        <div class="col-sm-8">
                            <input id="phone" class="form-control" name="phone" value="{{ Auth::user()->phone ? Auth::user()->phone : '' }}" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="line_id" class="col-sm-4 col-form-label">Line id:</label>
                        <div class="col-sm-8">
                            <input id="line_id" class="form-control" name="line_id" value="{{ Auth::user()->line_id ? Auth::user()->line_id : '' }}" required>
                        </div>
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
<!-- Add to Cart Modal -->
<div class="modal fade" id="addToCartModal" tabindex="-1" aria-labelledby="addToCartModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
    <div class="modal-content">
        <div class="modal-header">
        <h5 class="modal-title" id="addToCartModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
            <div class="modal-body">
                <div class="col">
                    <h6>เงื่อนไขการยืม</h6>
                    <p class="align-top" id="borrowConditions"></p>
                </div>
                    
                <div class="mb-3 row">
                    <label for="quantity" class="col-sm-4 col-form-label">ระบุจำนวนที่ต้องการ:</label>
                    <div class="col-sm-8">
                        <input id="quantity" class="form-control" name="quantity" value="">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                <button type="button" class="btn btn-primary" onclick="addToCart()">เพิ่ม</button>
            </div>
    </div>
    </div>
</div>
@endsection

@section('script')
    <script>
        // Popovers
        let popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        let popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl)
        })

        // Cell Column Index For references
        const nameColumnNumber = document.getElementById("mainTableHeadName").cellIndex;
        const conditionColumnNumber = document.getElementById("mainTableHeadCondition").cellIndex;
        // datepicker
        let now = new Date();
        let oneYearfromNow = new Date(new Date().setFullYear(new Date().getFullYear() + 1));
        const start = datepicker('#start_date', { id: 1 }, {startDate: new Date(2021, 0, 1)});
        const end = datepicker('#due_date', { id: 1 });
        document.getElementById('start_date').onkeydown = function(e) {e.preventDefault()};
        document.getElementById('due_date').onkeydown = function(e) {e.preventDefault()};
        //search bar
        function searchItem() {
            // Declare variables
            let input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("itemNameInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("itemTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        //check empty table, list
        function checkEmptyTable(tableID) {
            let table = document.getElementById(tableID);
            // console.log(table.tBodies[0].rows.length);
            if (table.tBodies[0].rows.length == 0) {
                document.getElementById(tableID).childNodes[3].innerHTML = `<tr><td colspan="4" class="text-center">ไม่มีอุปกรณ์ที่เลือกไว้</td></tr>`;
            }
        }
        // function checkEmptyList

        //item
        const myModal = new bootstrap.Modal(document.getElementById("addToCartModal"), {});
        let cart = new Object();
        let itemID = null;
        //Add items to cart
        let addToCartButtonList = document.querySelectorAll('[id^="buttonAddToCartModal"]');
        for (let i = 0; i < addToCartButtonList.length; i++) {
            addToCartButtonList[i].addEventListener("click", function(event) {
                itemID = parseInt(event.target.id.replace('buttonAddToCartModal',''));
                document.getElementById("addToCartModalLabel").innerText = document.querySelectorAll('tr[class="mainList"]')[itemID - 1].querySelectorAll('td')[nameColumnNumber].innerText;
                document.getElementById("borrowConditions").innerText = document.querySelectorAll('tr[class="mainList"]')[itemID - 1].querySelectorAll('td')[conditionColumnNumber].innerText;
                myModal.toggle();
            });
        };

        //validate quantity
        // function validateQuantity () {
        //     if (!/^[1-9]\d*$/gm.test(quantity)) {
        //         return;
        //     } else {
        //         document.querySelector('button[onclick="addToCart()"]').;
        //     }
        // }

        function addToCart () {
            let quantity = parseInt(document.getElementById("quantity").value);
            if (!/^[1-9]\d*$/gm.test(quantity)) {
                return;
            }
            if (cart.hasOwnProperty(itemID)) {
                cart[itemID] += quantity;
            } else {
                cart[itemID] = quantity;
            }
            updateCheckoutButton();
            myModal.toggle()
        }
        
        function deleteFromCart (event) {
            itemID = parseInt(event.target.id.replace('buttonDelete',''));
            if (cart.hasOwnProperty(itemID)) {
                document.getElementById('checkoutTable').deleteRow(event.target.parentNode.parentNode.rowIndex);
                document.getElementById("conditionsList").removeChild(document.getElementById(`checkoutCondition${itemID}`));
                delete cart[itemID];
            } else {
                //cart[itemID] = parseInt(document.getElementById("quantity").value);
            }
            updateCheckoutButton();
            checkEmptyTable('checkoutTable');
        }

        function updateCheckoutButton () {
            document.getElementById("quantity").value = '';
            document.getElementById("badgeItemCount").innerText = Object.keys(cart).length;
        }

        // Checkout
        
        document.getElementById("buttonCheckout").addEventListener("click", function () {
            let innerHTMLofItemList = '';
            let innerHTMLofConditions = '';
            for (const id in cart) {
                let name = document.querySelectorAll('tr[class="mainList"]')[id - 1].querySelectorAll('td')[nameColumnNumber].innerText.toString();
                let condition = document.querySelectorAll('tr[class="mainList"]')[id - 1].querySelectorAll('td')[conditionColumnNumber].innerText.toString();
                innerHTMLofItemList += `<tr>
                    <td>${id}</td>
                    <td>${name}</td>
                    <td>${cart[id]}</td>
                    <td class="fit">
                        <button type="button" class="btn btn-danger" id="${'buttonDelete' + id}" onclick="deleteFromCart(event)">
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16" style="pointer-events: none">
                                <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"></path>
                            </svg>
                        </button>
                    </td>
                </tr>`;

                innerHTMLofConditions += condition ? `<li id="checkoutCondition${id}">${name}: ${condition}</li>` : '';
            }
            document.getElementById("checkoutListBody").innerHTML = Object.keys(cart).length ?  innerHTMLofItemList : '';
            checkEmptyTable('checkoutTable');
            document.getElementById("conditionsList").innerHTML = innerHTMLofConditions;
        });

        //send data

        window.addEventListener( "load", function () {
            function sendData() {
                const XHR = new XMLHttpRequest();

                // Bind the FormData object and the form element
                const FD = new FormData( form );
                FD.append('itemCart', JSON.stringify(cart));

                // Define what happens on successful data submission
                XHR.addEventListener( "loadend", function(event) {
                    location.reload();
                } );

                // Define what happens in case of error
                XHR.addEventListener( "error", function( event ) {
                    alert( 'Oops! Something went wrong.' );
                } );

                // Set up our request
                XHR.open( "POST", "/home/newlog" );

                // The data sent is what the user provided in the form
                XHR.send( FD );

            }

            // Access the form element...
            const form = document.getElementById( "checkoutForm" );

            // ...and take over its submit event.
            form.addEventListener( "submit", function ( event ) {
                console.log(event);
                event.preventDefault();
                sendData();
            } );
        } );
    </script>
@endsection
@else
@section('content')
<div class="container">
    <div>
        <h1 class="text-center">ระเบียบการยืม-คืนพัสดุ</h1>
        <p >    
                
            
                <ol>
                    <li>ติดต่อเพื่อยืมพัสดุล่วงหน้า อย่างน้อย 3 วัน มาทาง Line official BORROW SMCU (<a href="https://lin.ee/tsfxuhW">https://lin.ee/tsfxuhW</a>)</li>
                    <li>ติดต่อตกลงวันที่คืนพัสดุทุกครั้งก่อนนำมาคืน</li>
                    <li>ยืมพัสดุได้ไม่เกิน 14 วัน</li>
                    <li>หากยืมพัสดุเกินวันที่ตกลงกันไว้ โปรดแจ้งล่วงหน้าและชี้แจงเหตุผล</li>
                    <li>เมื่อคืนพัสดุแล้ว ต้อง “ถ่ายรูป” การคืนพัสดุเป็นหลักฐานลงในรูปแบบที่กำหนดไว้</li>
                </ol>
                หมายเหตุ: ข้อมูลส่วนบุคคลได้แก่ ชื่อ-นามสกุล ชั้นปี เบอร์โทร และ/หรือ ไลน์ไอดี จะถูกนำไปใช้ระบุตัวตนเพื่อติดต่อสื่อสารเกี่ยวกับการยืมพัสดุเท่านั้น 
                <br>
                ข้อมูลของท่านจะถูกเก็บเป็นความลับ โดยผู้มีสิทธิ์เข้าถึงข้อมูลของเว็บไซต์นี้ จะมีเพียงอุปนายกผู้ดูแลพัสดุ และประธานฝ่ายสวัสดิการและพัสดุ สโมสรนิสิตคณะแพทยศาสตร์ จุฬาลงกรณ์มหาวิทยาลัยเท่านั้น
                <br>
                ระบบจะทำการลบธุรกรรมการยืมอุปกรณ์ของท่านภายใน 3 เดือนนับจากวันยืม หากท่านต้องการลบข้อมูลล่วงหน้าหรือต้องการยกเลิกคำยินยอมการให้ข้อมูลส่วนบุคคล กรุณาติดต่อฝ่ายสวัสดิการและพัสดุ สโมสรนิสิตคณะแพทยศาสตร์ จุฬาลงกรณ์มหาวิทยาลัยผ่านทาง Line official BORROW SMCU
        </p>
        <form id="agreeform">
            @csrf
            <div class="form-check">
            <input class="form-check-input" type="checkbox" value="1" id="agreeToTerms" name="agreeToTerms" required>
            <label class="form-check-label" for="agreeToTerms">
                ยอมรับระเบียบ
            </label>
            <div class="invalid-feedback">

            </div>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="checkbox" value="1" id="dataConsent" name="dataConsent" required>
                <label class="form-check-label" for="dataConsent">
                    ให้ความยินยอมในการเข้าถึงข้อมูลส่วนบุคคล
                </label>
                <div class="invalid-feedback">
                </div>
            </div>
            <input type="submit" class="btn btn-primary mt-3" value="ตกลง">
        </form>
            
    </div>
    <div class="row justify-content-center">
        
    </div>
</div>
@endsection
@section('script')
    <script>
        //send data

        window.addEventListener( "load", function () {
            function sendData() {
                const XHR = new XMLHttpRequest();

                // Bind the FormData object and the form element
                const FD = new FormData( form );

                // Define what happens on successful data submission
                XHR.addEventListener( "loadend", function(event) {
                    location.reload();
                } );

                // Define what happens in case of error
                XHR.addEventListener( "error", function( event ) {
                    alert( 'Oops! Something went wrong.' );
                } );

                // Set up our request
                XHR.open( "POST", "/home/agree" );

                // Add the required HTTP header for form data POST requests
                
                //XHR.setRequestHeader( 'Content-Type', 'application/x-www-form-urlencoded' );

                // The data sent is what the user provided in the form
                XHR.send( FD );

            }

            // Access the form element...
            const form = document.getElementById( "agreeform" );

            // ...and take over its submit event.
            form.addEventListener( "submit", function ( event ) {
                event.preventDefault();
                sendData();
            } );
        } );
    </script>
@endsection
@endif