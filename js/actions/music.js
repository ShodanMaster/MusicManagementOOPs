$(document).ready(function () {

    var table = $('#musicsTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: "routes/music.php",
            type: "GET"
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
                        <button class="btn btn-sm btn-info edit-btn"  data-bs-toggle="modal" data-bs-target="#editTaskModal" data-id="${row.id}" data-title="${row.title}" data-priority="${row.priority}">Edit</button>
                        <button class="btn btn-sm btn-danger delete-btn" data-id="${row.id}">Delete</button>
                    `;
                }
            }
        ],
        pageLength: 5,
        lengthMenu: [[5, 10, 25, -1], [5, 10, 25, "All"]]
    });

    $(document).on('submit', '#music-form', function (e) {
        e.preventDefault();

        var formData = new FormData(this);

        $.ajax({
            type: "POST",
            url: "routes/music.php?action=add",
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                if(response.status === 200){
                    Swal.fire({
                        title: "Success!",
                        text: response.message,
                        icon: "success",
                        confirmButtonText: "OK",
                    }).then(() => {
                        table.ajax.reload();
                        $('#addMusicModal').modal('hide');
                    });
                } 
            }
        });

    });
    
});