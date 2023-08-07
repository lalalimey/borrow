@extends('layouts.app')
<?php
use App\Models\KuruModel;
?>
@if (Auth::user()->agreetotermsandconditions && Auth::user()->dataconsent)
    @section('content')
        <div class="container">
            <div class="row justify-content-center">
                    <?php
                    $kurus = KuruModel::all();
                    ?>
                <table class="table align-middle" id="itemTable">
                    <thead>
                    <tr>
                        <th scope="col">id</th>
                        <th scope="col" id="mainTableHeadName">ชื่อ</th>
                        <th scope="col">ฝ่าย</th>
                        <th scope="col">สถานที่</th>
                        <th scope="col">งบ</th>
                        <th scope="col">ปีที่เบิก</th>
                        <th class="fit"></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($kurus as $kuru)
                            <td>{{ $kuru->number }}</td>
                            <td>{{ $kuru->name }}</td>
                            <td>{{ $kuru->division }}</td>
                            <td>{{ $kuru->storage }}</td>
                            <td>{{ $kuru->budget }}</td>
                            <td>{{ $kuru->year }}</td>
                            <td>
                                <input type="checkbox">
                            </td>
                        @endforeach
                    </tbody>

            </div>
        </div>
    @endsection
@else
    @section('content')
        <div class="container">
            <div>
                <h1 class="text-center">ระเบียบการยืม-คืนพัสดุ</h1>
                <p >


                <ol>
                    <li>ติดต่อเพื่อยืมพัสดุล่วงหน้า อย่างน้อย 3 วัน มาทาง Line official SMCU BORROW (<a href="https://lin.ee/tsfxuhW">https://lin.ee/tsfxuhW</a>)</li>
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

