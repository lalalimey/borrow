<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>ระบบยืม-คืนพัสดุ</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.gstatic.com">
        <link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;1,100;1,200;1,300;1,400;1,500;1,600&display=swap" rel="stylesheet"> 

        <!-- Styles -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-eOJMYsd53ii+scO/bJGFsiCZc+5NDVN2yr8+0RDqr0Ql0h+rP48ckxlpbzKgwra6" crossorigin="anonymous">

        <style>
            body {
                font-family: 'Kanit', sans-serif;
            }
        </style>
    </head>
    <body>
        <div class="px-4 py-5 my-5 ">
            <img class="d-block mx-auto mb-4" src="https://docchula.com/assets/smcu.png" alt="" width="120">
            <h1 class="display-5 fw-bold text-center mb-1">ระบบยืม-คืนพัสดุ</h1>
            <h3 class="text-center mb-5">สโมสรนิสิตคณะแพทยศาสตร์ จุฬาลงกรณ์มหาวิทยาลัย</h3>
            <div class="card col-md-6 mx-auto">
                <div class="card-header">
                    ระเบียบการยืม-คืนพัสดุ
                </div>
                <div class="card-body">
                    <p class="card-text text-left">
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
                </div>    
            </div>
            <div class="d-grid gap-2 d-sm-flex justify-content-sm-center mt-3">
                <a href="/redirect" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Login ด้วยบัญชี docchula.com</a>
            </div>
            {{-- <div class="col-lg-6 mx-auto">
                <p class="lead mb-4">Quickly design and customize responsive mobile-first sites with Bootstrap, the world’s most popular front-end open source toolkit, featuring Sass variables and mixins, responsive grid system, extensive prebuilt components, and powerful JavaScript plugins.</p>
                <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
                    <a href="/redirect" class="btn btn-primary btn-lg active" role="button" aria-pressed="true">Login ด้วยบัญชี docchula.com</a>
                </div>
            </div> --}}
        </div>
    </body>
</html>
