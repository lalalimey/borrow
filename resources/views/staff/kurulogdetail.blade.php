@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <h2>รายการยืมครุภัณฑ์หมายเลข {{$id}}</h2>
                <div class="row">
                    <div class="col-md-6">
                        <h3>ข้อมูล</h3>
                    </div>
                    <div class="col-md-6">
                        <h3>รายการการยืม</h3>
                        <?php
                        use App\Models\Kuru_logModal;
                        use App\Models\KuruModel;
                        $logs = Kuru_logModal::where('id',$id)->get();
                            ?>
                        @foreach ($logs as $log)
                            @php
                                $lists = explode(', ', $log->item_list);
                            @endphp

                            @foreach ($lists as $item)
                                <?php
                                    $itemused =KuruModel::where('number',$item)->first();
                                    ?>
                                {{ $item }}<br>
                            @endforeach
                        @endforeach

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('script')

@endsection
