@extends('layouts.app')
@section('content')
    <div class="container">
        @if(session('error'))
            <div class="alert alert-danger" id="alertmass">
                {{ session('error') }}
            </div>
            <script>
                // Automatically hide the success message after 5 seconds (5000 milliseconds)
                setTimeout(function() {
                    document.getElementById('alertmass').style.display = 'none';
                }, 4000); // Change the time as needed
            </script>
        @endif
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

        <input type="text" class="form-control" id="itemNameInput" onkeyup="searchItem()" placeholder="Search items...">
        <br>
        <div class="flex-reverse mb-3">
            <div class="d-flex flex-row-reverse">
                <!-- Add onclick event to call the showCheckoutModal() function -->
                <button type="button" class="btn btn-primary ms-2" id="borrow" onclick="showCheckoutModal()">
                    borrow
                </button>
                @if(Auth::user()->status == 'STAFF')
                    <a href="{{route('addkuru')}}"><button type="button" class="btn btn-primary ms-2">เพิ่มครุภัณฑ์</button></a>
                    <a href="/staff/kuru/find"><button type="button" class="btn btn-warning">แก้ไขครุภัณฑ์</button></a>
                @endif
            </div>
        </div>
        <div class="row justify-content-center">
            <div class="scrollable-div" style="height: 65vh; width: 100vw; overflow-y: auto;">
                <table class="table align-middle" id="itemTable">
                    <thead>
                    <tr>
                        <th scope="col"><a href="/kuru/id" class="text-dark text-decoration-none" id="sortbyid-link">id</a></th>
                        <th scope="col" id="mainTableHeadName"><a href="/kuru/name" class="text-dark text-decoration-none" id="sortbyid-name">ชื่อ</a></th>
                        <th scope="col">ฝ่าย</th>
                        <th scope="col">สถานที่</th>
                        <th scope="col">งบ</th>
                        <th scope="col"><a href="/kuru/year" class="text-dark text-decoration-none" id="sortbyid-year">ปีที่เบิก</a></th>
                        <th scope="col">สถานะ</th>
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
                            @if($kuru->status == 'normal')
                                <td>{{ $kuru->status }}</td>
                            @elseif($kuru->status == 'pending')
                                <td><span class="text-secondary">{{ $kuru->status }}</span></td>
                            @elseif($kuru->status == 'broken')
                                <td><span class="text-danger">{{ $kuru->status }}</span></td>
                            @elseif($kuru->status == 'borrowed')
                                <td><span class="text-primary">{{ $kuru->status }}</span></td>
                            @endif
                            <td>
                                @if($kuru->status == 'normal')
                                    <input type="checkbox" class="item-checkbox" name="selected_items[]" value="{{ $kuru->number }}">
                                @else
                                    <input type="checkbox" class="item-checkbox" name="selected_items[]" value="{{ $kuru->number }}" disabled>
                                @endif
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
                    <h1 class="modal-title fs-5" id="checkoutModalLabel">checkout</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <!-- Display the selected items' details here -->
                    <ul id="selectedItemsList">
                        <!-- Selected item details will be added dynamically here -->
                    </ul>
                    <hr>
                    <form action="{{ route('save') }}" method="post">
                        @csrf
                        <div class="m-3">
                            <div class="mb-3 row">
                                <label for="year" class="col-sm-4 col-form-label" >การดำเนินการ:</label>
                                <div class="col-sm-8">
                                    <select  class="form-select" name="process" id="process" onchange="toggleInputField()" required>
                                        <option selected='selected' value="1">ยืม</option>
                                        @if(Auth::user()->status == 'STAFF')
                                            <option value="2">แจ้งซ่อม</option>
                                            <option value="3">checkup</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="purposeTextArea" class="form-label">จุดประสงค์:</label>
                                <textarea class="form-control" name="purpose" id="purposeTextArea" rows="3" value="" required></textarea>
                            </div>
                            <div id="noshow">
                                <div class="mb-3 row">
                                    <label for="place" class="col-sm-4 col-form-label">สถานที่ใช้งาน:</label>
                                    <div class="col-sm-8">
                                        <input id="place" class="form-control" name="place" value="" required>
                                        <input id="list" class="form-control" name="list" style="display: none" required>
                                    </div>
                                </div>
                                <div class="mb-3 row">
                                    <label for="start_date" class="col-sm-4 col-form-label">วันที่ยืม:</label>
                                    <div class="col-sm-8">
                                        <input id="start_date" class="form-control" name="start_date" value="" required>
                                    </div>
                                </div>
                                <div class=" row">
                                    <label for="due_date" class="col-sm-4 col-form-label">วันที่คืน:</label>
                                    <div class="col-sm-8">
                                        <input id="due_date" class="form-control" name="due_date" value="" disabled required>
                                        <p>ยืมครุภัณฑ์ได้ไม่เกิน 14 วัน</p>
                                    </div>
                                </div>
                                <h6 class="pt-1">ข้อมูลสำหรับการติดต่อ</h6>
                                <div class="mb-3 row">
                                    <label for="phone" class="col-sm-4 col-form-label">เบอร์โทรศัพท์มือถือ หรือ ช่องทางติดต่อที่สะดวก:</label>
                                    <div class="col-sm-8">
                                        <input id="phone" class="form-control" name="phone" value="{{ Auth::user()->phone ? Auth::user()->phone : '' }}" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                    <script>
                        function toggleInputField() {
                            var choice = document.getElementById('process').value;
                            var inputField = document.getElementById('noshow');

                            if (choice === 'ยืม') {
                                inputField.style.display = 'block';
                            } else {
                                inputField.style.display = 'none';
                            }
                        }
                    </script>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="submitBtn">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        var linkElement = document.getElementById("sortbyid-link");

        // Check if the current URL matches the desired condition
        if (window.location.pathname === "/kuru/id") {
            // Update the link's content with SVG and text
            linkElement.innerHTML = 'id <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>';
        }
        var linkElement1 = document.getElementById("sortbyid-name");
        if (window.location.pathname === "/kuru/name") {
            // Update the link's content with SVG and text
            linkElement1.innerHTML = 'ชื่อ <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>';
        }
        var linkElement2 = document.getElementById("sortbyid-year");
        if (window.location.pathname === "/kuru/year") {
            // Update the link's content with SVG and text
            linkElement2.innerHTML = 'ปีที่เบิก <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-caret-down-fill" viewBox="0 0 16 16"><path d="M7.247 11.14 2.451 5.658C1.885 5.013 2.345 4 3.204 4h9.592a1 1 0 0 1 .753 1.659l-4.796 5.48a1 1 0 0 1-1.506 0z"/></svg>';
        }

        $(document).ready(function() {
            $('#submitBtn').on('click', function() {
                var form = $('#checkoutModal').find('form');

                // Submit the form
                form.submit();
            });
        });

        $(document).ready(function () {
            var startDatePicker = $('#start_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Set the start date to today
            });

            var dueDatePicker = $('#due_date').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true,
                todayHighlight: true,
                startDate: new Date() // Set the start date to today
            });

            startDatePicker.on('changeDate', function (e) {
                var selectedStartDate = e.date;
                var maxDueDate = new Date(selectedStartDate);
                maxDueDate.setDate(maxDueDate.getDate() + 14); // 14 days after start_date

                // Check if the current due_date is less than 14 days after the new start_date
                if (dueDatePicker.datepicker('getDate') < maxDueDate) {
                    dueDatePicker.datepicker('setDate', maxDueDate);
                }

                // Enable due_date field and set date range based on start_date
                dueDatePicker.prop('disabled', false);
                dueDatePicker.datepicker('setStartDate', selectedStartDate);
                dueDatePicker.datepicker('setEndDate', maxDueDate);
            });
        });


        function searchItem() {
            // Declare variables
            let input, filter, table, tr, td, i, txtValue, nm, nmValue;
            input = document.getElementById("itemNameInput");
            filter = input.value.toUpperCase();
            table = document.getElementById("itemTable");
            tr = table.getElementsByTagName("tr");

            // Loop through all table rows, and hide those who don't match the search query
            for (i = 0; i < tr.length; i++) {
                td = tr[i].getElementsByTagName("td")[1];
                nm = tr[i].getElementsByTagName("td")[0];
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    nmValue = nm.textContent || nm.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1 || nmValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }
            }
        }

        function showCheckoutModal() {
            // Get all checkboxes with class "item-checkbox"
            const checkboxes = document.querySelectorAll('.item-checkbox');
            const selectedNumbers = [];

            // Loop through all checkboxes to find the selected items
            checkboxes.forEach((checkbox) => {
                if (checkbox.checked) {
                    selectedNumbers.push(checkbox.value);
                }
            });
            const checkboxe = document.querySelectorAll('input[type="checkbox"]:checked');
            const namesArray = [];

            checkboxe.forEach((checkbox) => {
                namesArray.push(checkbox.value);
            });
            // You can now prefill the input box with the namesArray values.
            const prefilledInput = document.getElementById('list');
            prefilledInput.value = namesArray.join(', ');
            // Now you have the selectedNumbers array containing the numbers of the selected items
            // You can pass this array to the modal or do further processing as needed

            // Fetch the selected items' details from the table and display them in the modal content
            const modalBody = document.querySelector('.modal-body');
            const selectedItemsList = modalBody.querySelector('#selectedItemsList');
            selectedItemsList.innerHTML = ''; // Clear previous content

            if (selectedNumbers.length > 0) {
                const tableRows = document.querySelectorAll('#itemTable tbody tr');
                selectedNumbers.forEach((number) => {
                    // Find the row containing the selected item based on the number
                    const row = Array.from(tableRows).find((r) => {
                        const itemNumberCell = r.querySelector('td:first-child');
                        return itemNumberCell.textContent.trim() === number;
                    });

                    if (row) {
                        // Get the item details from the row
                        const name = row.querySelector('td:nth-child(2)').textContent;
                        const division = row.querySelector('td:nth-child(3)').textContent;

                        // Display the selected item details in the modal
                        const listItem = document.createElement('li');
                        listItem.textContent = `${number} ${name}`;
                        selectedItemsList.appendChild(listItem);
                    }
                });
            } else {
                // If no items are selected, display a message in the modal
                const noItemsMessage = document.createElement('p');
                noItemsMessage.textContent = 'No items selected.';
                selectedItemsList.appendChild(noItemsMessage);
            }

            // Show the modal
            const modal = new bootstrap.Modal(document.getElementById('checkoutModal'));
            modal.show();
        }
    </script>
@endsection
