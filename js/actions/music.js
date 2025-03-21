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
                        <button class="btn btn-sm btn-success playlist-btn" data-bs-toggle="modal" data-bs-target="#addToPlaylistModal" data-id="${row.id}" data-music="${row.music}">
                            Add to Playlist
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
        
        $('#music-form')[0].reset();
        $('#musicId').val("");
        
        $('#addMusicModalLabel').text('Add Music');
        $('#saveBtn').text('Save Music');
        
        $('#addMusicModal').modal('show');
    });
    
    $('#musicsTable').on('click', '.edit-btn', function () {
        var musicId = $(this).data('id');
        var musicTitle = $(this).data('music');
        var musicCreator = $(this).data('creator');
        
        $('#musicId').val(musicId);
        $('#music').val(musicTitle);
        $('#creator').val(musicCreator);
        
        $('#addMusicModalLabel').text('Edit Music');
        $('#saveBtn').text('Update Music');

        $('#addMusicModal').modal('show');
    });
    
    $('#musicsTable').on('click', '.playlist-btn', function () {
        var musicId = $(this).data('id');
        var musicTitle = $(this).data('music');

        $('#playlistmusicId').val(musicId);
        $('#addToPlaylistModalLabel').text('Add ' + musicTitle + ' to Playlist');
        $('#saveBtn').text('Update Music');

        $('#addToPlaylistModal').modal('show');

        $.ajax({
            type: "get",
            url: "routes/playlist.php",
            dataType: "json",
            success: function (response) {
                var $playlistSelect = $('#playlist');
                $playlistSelect.empty();

                $playlistSelect.append('<option value="" selected disabled>-- Select Playlist --</option>');
                
                if (response.data && response.data.length > 0) {
                    $.each(response.data, function (index, playlist) {
                        $playlistSelect.append('<option value="' + playlist.id + '">' + playlist.playlist + '</option>');
                    });
                } else {
                    $playlistSelect.append('<option value="" disabled>No playlists available</option>');
                }
            },
            error: function (xhr, status, error) {
                console.error("Error fetching playlists:", error);
            }
        });
    });
    
    $('#musicsTable').on('click', '.delete-btn', function () {
        var musicId = $(this).data('id');
        
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
                            "Failed to delete the music item.",
                            "error"
                        );
                    }
                });
            }
        });
        
    });
    
    $('#music-form').on('submit', function (e) {
        e.preventDefault(); 

        var music = $('#music').val();
        var creator = $('#creator').val();
        
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

    $('#addMusicModal').on('hidden.bs.modal', function () {
        $(this).find('form')[0].reset();
    });

    $('#addtoplaylist-form').on('submit', function (e) {
        e.preventDefault();

        console.log("Inside");

        var formData = new FormData(this);
        formData.append('action', 'addToPlaylist');

        $.ajax({
            type: "POST",
            url: "routes/musicPlaylist.php",
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
                        $('#addToPlaylistModal').modal('hide');
                    });
                } else {
                    Swal.fire({
                        title: "OOPS!",
                        text: response.message,
                        icon: "info",
                        confirmButtonText: "OK",
                    }).then(() => {
                        
                        $('#addToPlaylistModal').modal('hide');
                    });
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

});
