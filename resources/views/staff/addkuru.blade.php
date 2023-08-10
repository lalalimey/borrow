@extends('layouts.app')

@section('content')
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success" id="success-message">
                {{ session('success') }}
            </div>
            <script>
                // Automatically hide the success message after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    document.getElementById('success-message').style.display = 'none';
                }, 4000); // Change the time as needed
            </script>
        @endif
        <div class="row">
            <div class="col-md-6">
                <!-- Content for the first column goes here -->
                <h2>เพิ่มครุภัณฑ์ทีละรายการ</h2>
                <p>เหมาะสำหรับการเพิ่มครุภัณฑ์จำนวนไม่มาก สามารถทำได้อย่างรวดเร็ว</p>
                <div class="container m-2">
                    <h3>ข้อมูลครุภัณฑ์</h3>
                    <form action="{{ route('addone') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="number">Number:</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>
                        <div class="form-group">
                            <label for="name">Name:</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="division">Division:</label>
                            <input type="text" class="form-control" id="division" name="division" required>
                        </div>
                        <div class="form-group">
                            <label for="storage">Storage:</label>
                            <input type="text" class="form-control" id="storage" name="storage" required>
                        </div>
                        <div class="form-group">
                            <label for="budget">Budget:</label>
                            <input type="text" class="form-control" id="budget" name="budget" required>
                        </div>
                        <div class="form-group">
                            <label for="year">Year:</label>
                            <input type="text" class="form-control" id="year" name="year" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>

            </div>
            <div class="col-md-6">
                <!-- Content for the second column goes here -->
                <h2>เพิ่มครุภัณฑ์โดย excel</h2>
                <p>เหมาะสำหรับการเพิ่มครุภัณฑ์จำนวนมาก</p>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('addfromfile') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="file" name="excel_file" accept=".xls, .xlsx">
                    <button type="submit" class="btn btn-primary">Upload</button>
                </form>
                <br>
                <h2>วิธีการใช้งาน</h2>
                <p>1.ดาวน์โหลด template ของ excel ด้านล่าง</p>
                <a href="{{ route('download.excel') }}" class="btn btn-primary mb-3">Download Excel File</a>
                <p>2.ใส่ข้อมูลของครุภัณฑ์แต่ละรายการให้ครบทุก column ซึ่งได้แก่ เลขครุภัณฑ์ ชื่อครุภัณฑ์ ฝ่าย ที่จัดเก็บ งบประมาณ และ ปี</p>
                <p>3.upload file ด้านบนแล้วกด "upload"</p>
            </div>
        </div>
    </div>
@endsection


