<!DOCTYPE html>
<html>

<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <title>VeroTask</title>
  
</head>

<body>
    <h1>VeroTask</h1><br>


    <div class="input-group mb-3">
        <span class="input-group-text">
            <i class="fas fa-search fa-sm"></i>
        </span>
        <input type="text" class="form-control-sm" id="searchInput" placeholder="Search for tasks">
    </div>


    <!-- Button to open the modal -->
    <div class="container">
        <div class="row">
            <div class="col text-end">
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#imageModal">
                    Open Image Modal
                </button>
            </div>
        </div>
    </div>

    <table id="tasksTable" class="table table-striped">
        <thead>
            <tr>
                <th scope="col">task</th>
                <th scope="col">title</th>
                <th scope="col">description</th>
                <th scope="col">colorCode</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tasks as $task)
            <tr>
                <th scope="row">{{ $task['task'] }}</th>
                <td>{{ $task['title'] }}</td>
                <td>{{ $task['description'] }}</td>
                <td style="background-color: {{ $task['colorCode'] }};">&nbsp;</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Modal for image selection -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Select Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="file" id="imageFileInput" accept="image/*">
                    <div id="selectedImagePreview" ></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="selectImage()">Select</button>
                </div>
            </div>
        </div>
    </div>

</body>

</html>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
</script>

<script>
    // Function to handle the search
    function searchTasks() {
        // Get input element and filter value
        var input = document.getElementById('searchInput');
        var filter = input.value.toUpperCase();

        // Get the table and table rows
        var table = document.getElementById('tasksTable');
        var rows = table.getElementsByTagName('tr');

        // Loop through all table rows, hide those that don't match the search query
        for (var i = 0; i < rows.length; i++) {
            var taskData = rows[i].textContent.toUpperCase();
            if (taskData.indexOf(filter) > -1) {
                rows[i].style.display = '';
            } else {
                rows[i].style.display = 'none';
            }
        }
    }

    // Event listener for the input field to trigger search on input
    document.getElementById('searchInput').addEventListener('input', searchTasks);




    // Function to fetch updated tasks data and refresh the table every 60 minutes
    function autoRefresh() {
        setInterval(function() {
            // Make an AJAX request to get updated tasks data
            fetch('/tasks') // Update the endpoint if necessary
                .then(response => response.json())
                .then(data => {
                    // Replace the table content with the updated tasks data
                    var tableBody = document.querySelector('#tasksTable tbody');
                    tableBody.innerHTML = '';

                    data.forEach(task => {
                        var row = document.createElement('tr');
                        row.innerHTML = `
                            <th scope="row">${task.task}</th>
                            <td>${task.title}</td>
                            <td>${task.description}</td>
                            <td style="background-color: ${task.colorCode};">&nbsp;</td>
                        `;
                        tableBody.appendChild(row);
                    });
                })
                .catch(error => {
                    console.error('Error fetching data:', error);
                });
        }, 3600000); // 60 minutes in milliseconds
    }

    // Call the autoRefresh function to start auto-updating the table
    autoRefresh();


    function selectImage() {
        // Get the selected image file
        const fileInput = document.getElementById('imageFileInput');
        const selectedFile = fileInput.files[0];

        // Display the selected image preview
        const selectedImagePreview = document.getElementById('selectedImagePreview');
        const image = document.createElement('img');
        image.src = URL.createObjectURL(selectedFile);
        image.classList.add('img-fluid');
        selectedImagePreview.innerHTML = '';
        selectedImagePreview.appendChild(image);
    }
</script>