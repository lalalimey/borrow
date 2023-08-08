@extends('layouts.app')
<?php
use App\Models\KuruModel;
?>
@section('content')
    <div class="container">
        <form action="{{ route('search') }}" method="GET">
            <input type="text" name="query" placeholder="Search items...">
            <button type="submit">Search</button>
            <div class="row justify-content-center">
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
                        <tr class="mainList">
                            <td>{{ $kuru->number }}</td>
                            <td>{{ $kuru->name }}</td>
                            <td>{{ $kuru->division }}</td>
                            <td>{{ $kuru->storage }}</td>
                            <td>{{ $kuru->budget }}</td>
                            <td>{{ $kuru->year }}</td>
                            <td>
                                <input type="checkbox" name="selected_items[]" value="{{ $kuru->id }}" @if(in_array($kuru->id, session('selected_items', []))) checked @endif>
                            </td>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </form>
    </div>
@endsection
