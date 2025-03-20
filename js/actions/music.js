$(document).ready(function () {

    var table = $('#musicsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "routes/music.php",
            type: "GET",
            error: function (xhr, error, thrown) {
                alert('Error: ' + error + ' - ' + thrown);
            }
        },
        columns: [
            { 
                data: null, 
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: "music" },
            { data: "creator" },
            { 
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal" data-bs-target="#editTaskModal" 
                                data-id="${row.id}" data-music="${row.music}" data-creator="${row.creator}">
                            Edit
                        </button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">
                            Delete
                        </button>
                    `;
                }
            }
        ],
        pageLength: 5,
        lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]]
    });

    // Event delegation for handling Edit button click
    $('#musicsTable').on('click', '.edit-btn', function () {
        var musicId = $(this).data('id');
        var musicTitle = $(this).data('music');
        var musicCreator = $(this).data('creator');

        // Set the values in the modal
        $('#editTaskModal').find('input[name="musicId"]').val(musicId);
        $('#editTaskModal').find('input[name="music"]').val(musicTitle);
        $('#editTaskModal').find('input[name="creator"]').val(musicCreator);
    });

    // Event delegation for handling Delete button click
    $('#musicsTable').on('click', '.delete-btn', function () {
        var musicId = $(this).data('id');
        
        // You can trigger the confirmation dialog here or directly delete the music item
        if (confirm("Are you sure you want to delete this music?")) {
            $.ajax({
                url: 'routes/music.php',
                type: 'POST',
                data: {
                    action: 'delete',
                    id: musicId
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    if (result.status === 200) {
                        // Refresh the table to show the updated data
                        table.ajax.reload();
                    } else {
                        alert(result.message);
                    }
                },
                error: function() {
                    alert('Error deleting the music item.');
                }
            });
        }
    });

    // Handle Add Music Form submission via AJAX
    $('#music-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var music = $('#music').val();
        var creator = $('#creator').val();

        // Validate inputs
        if (music.trim() === "" || creator.trim() === "") {
            alert("Please fill in both music and creator fields.");
            return;
        }

        // Send data via AJAX
        $.ajax({
            url: 'routes/music.php',
            type: 'POST',
            data: {
                action: 'add',
                music: music,
                creator: creator
            },
            success: function (response) {
                console.log(response);
                
                if (response.status === 200) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        table.ajax.reload();
                        $('#addMusicModal').modal('hide');
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function () {
                alert('Error adding the music.');
            }
        });
    });

    $('#addMusicModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });

});
