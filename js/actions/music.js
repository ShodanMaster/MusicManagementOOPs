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
                        <button class="btn btn-sm btn-info edit-btn" data-bs-toggle="modal" data-bs-target="#addMusicModal" 
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

        console.log(musicId);
        console.log(musicTitle);
        console.log(musicCreator);
        
        // Set the values in the modal
        $('#addMusicModal').find('input[name="musicId"]').val(musicId);
        $('#addMusicModal').find('input[name="music"]').val(musicTitle);
        $('#addMusicModal').find('input[name="creator"]').val(musicCreator);

        $('#addMusicModalLabel').html('Edit Music');
        $('#saveBtn').html('Update Music');
    });

    // Event delegation for handling Delete button click
    $('#musicsTable').on('click', '.delete-btn', function () {
        var musicId = $(this).data('id');
        
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
                    url: 'routes/music.php?action=delete',
                    type: 'POST',
                    data: {
                        action: 'delete',
                        musicId: musicId
                    },
                    success: function(response) {
                        if (response.status === 200) {
                            Swal.fire(
                                "Deleted!",
                                "Music has been deleted.",
                                "success"
                            );
                            table.ajax.reload();
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
                            "Failed to delete the music item.",
                            "error"
                        );
                    }
                });
            }
        });
        
    });

    // Handle Add Music Form submission via AJAX
    $('#music-form').on('submit', function (e) {
        e.preventDefault(); // Prevent the default form submission

        var music = $('#music').val();
        var creator = $('#creator').val();

        // Validate inputs
        if (music.trim() === "" || creator.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "Missing Information",
                text: "Please fill in both music and creator fields.",
                confirmButtonColor: "#3085d6"
            });
            return;
        }
        

        var formData = new FormData(this);
        // Send data via AJAX
        $.ajax({
            url: 'routes/music.php?action=add',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
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
