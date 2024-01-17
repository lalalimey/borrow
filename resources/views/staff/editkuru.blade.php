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
        @if(session('error'))
            <div class="alert alert-danger" id="error-message">
                {{ session('error') }}
            </div>
            <script>
                // Automatically hide the error message after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    document.getElementById('error-message').style.display = 'none';
                }, 10000); // Change the time as needed
            </script>
        @endif
        <div class="row">
            <div class="col-md-6">
                <!-- Content for the first column goes here -->
                <h2>ตรวจสอบ/แก้ไขรายการครุภัณฑ์</h2>
                <p><span class="text-danger">กรุณากรอกหมายเลขครุภัณฑ์ที่ต้องการจะแก้ไข</span></p>
                <div class="container">
                    <form action="{{ route('findkuru') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="number">Number:</label>
                            <input type="text" class="form-control" id="number" name="number" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2">Submit</button>
                    </form>
                </div>

            </div>
            <div class="col-md-6">
                @if(isset($kuru))
                    <!-- Display data from $kuru variable -->
                    <h2>ข้อมูลครุภัณฑ์หมายเลข {{$kuru->number}}</h2>
                    <!-- Add other fields as needed -->
                    <form action="{{ route('editkuru') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <label for="number">Number:</label>
                            <input type="text" class="form-control" name="number" value="{{ $kuru->number ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control" name="old_number" value="{{ $kuru->number ?? '' }}" required hidden>
                        </div>
                        <div class="form-group">
                            <label for="number">Name:</label>
                            <input type="text" class="form-control" name="name" value="{{ $kuru->name ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Division:</label>
                            <input type="text" class="form-control" name="division" value="{{ $kuru->division ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Storage:</label>
                            <input type="text" class="form-control" name="storage" value="{{ $kuru->storage ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Budget:</label>
                            <input type="text" class="form-control" name="budget" value="{{ $kuru->budget ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Year:</label>
                            <input type="text" class="form-control" name="year" value="{{ $kuru->year ?? '' }}" required>
                        </div>
                        <div class="form-group">
                            <label for="number">Detail:</label>
                            <input type="text" class="form-control" name="detail" value="{{ $kuru->detail ?? '' }}" required>
                        </div>
                        <button type="submit" class="btn btn-primary mt-2" onclick="return confirm('กรุณาตรวจสอบข้อมูลให้ถูกต้อง หลังจาก update แล้วจะไม่สามารถเรียกคืนข้อมูลเก่าได้')">Submit</button>
                    </form>
                    <form action="{{ route('deletekuru') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control" name="old_number" value="{{ $kuru->number ?? '' }}" required hidden>
                        </div>
                        <button type="submit" class="btn btn-danger mt-2" onclick="return confirm('this item will permanently delete')">delete item</button>
                    </form>
                @else
                    <h2 class="text-center">ไม่พบข้อมูลครุภัณฑ์ที่ท่านทำการค้นหา</h2>
                @endif
            </div>
        </div>
    </div>
@endsection


