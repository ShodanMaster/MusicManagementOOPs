$(document).ready( function () {
    
    var table = $('#playlistsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "routes/playlist.php",
            type: "GET",
            error: function (xhr, error, thrown) {
                alert('Error: ' + error + ' - ' + thrown);
                console.error("AJAX Error:", {
                    status: status,
                    error: error,
                    responseText: xhr.responseText});
            }
        },
        columns: [
            { 
                data: null, 
                render: function (data, type, row, meta) {
                    return meta.row + 1;
                }
            },
            { data: "playlist" },
            { 
                data: null,
                render: function (data, type, row) {
                    return `
                        <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal" data-bs-target="#addPlaylistModal" 
                                data-id="${row.id}" data-playlist="${row.playlist}">
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

    $('#createButton').on('click', function (e) {
        e.preventDefault();
        
        // Reset form fields
        $('#playlist-form')[0].reset();
        $('#playlistId').val(""); // Clear hidden input for ID
        
        // Set modal title and button text
        $('#addPlaylistModalLabel').text('Add Playlist');
        $('#saveBtn').text('Save Playlist');

        // Show the modal
        $('#addPlaylistModal').modal('show');
    });

    $('#playlist-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var playlist = $('#playlist').val();

        // Validate inputs
        if (playlist.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "Missing Information",
                text: "Please fill in both playlist and creator fields.",
                confirmButtonColor: "#3085d6"
            });
            return;
        }
        

        var formData = new FormData(this);
        formData.append('action','add');
        // Send data via AJAX
        $.ajax({
            url: 'routes/playlist.php',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                
                if (response.status === 200) {
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        table.ajax.reload();
                        $('#addPlaylistModal').modal('hide');
                    });
                } else {
                    alert(response.message);
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", {
                    status: xhr.status,
                    error: error,
                    responseText: xhr.responseText
                });
            
                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Error adding the playlist!",
                    footer: `<strong>${error}</strong>`
                });
            }
        });
    });

    

    $('#playlistsTable').on('click', '.delete-btn', function () {
        var playlistId = $(this).data('id');
        
        // You can trigger the confirmation dialog here or directly delete the music item
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to revert this!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#3085d6",
            confirmButtonText: "Yes, delete it!"
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: 'routes/playlist.php',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        playlistId: playlistId
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire(
                                "Deleted!",
                                "Playlist has been deleted.",
                                "success"
                            );
                            table.draw();
                        } else {
                            Swal.fire(
                                "Error!",
                                response.message,
                                "error"
                            );
                        }
                    },
                    error: function() {
                        Swal.fire(
                            "Error!",
                            "Failed to delete the playlist item.",
                            "error"
                        );
                    }
                });
            }
        });
        
    });
});