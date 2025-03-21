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
                                data-id="${row.id}" data-music="${row.playlist}">
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

});