@extends('layouts.app')
@section('content')
    <div class="container">
        <input type="text" class="form-control" id="itemNameInput" onkeyup="searchItem()" placeholder="Search items...">
        <br>
        <div class="flex-reverse mb-3">
            <div class="d-flex flex-row-reverse">
                <!-- Add onclick event to call the showCheckoutModal() function -->
                <button type="button" class="btn btn-primary" onclick="showCheckoutModal()">
                    borrow
                </button>
            </div>
        </div>
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
                        <td>{{ $kuru->status }}</td>
                        <td>
                            <input type="checkbox" class="item-checkbox" name="selected_items[]" value="{{ $kuru->number }}">
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <!-- Checkout Modal -->
    <div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
        <div class="modal-dialog">
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
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
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
                        listItem.textContent = `Number: ${number}, Name: ${name}, Division: ${division}`;
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
