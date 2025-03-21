<?php 
include("app/header.php")
?>

<!-- Music Modal -->
<div class="modal fade" id="addPlaylistModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h1 class="modal-title fs-5" id="addPlaylistModalLabel">Add Music</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="music-form">
        <input type="hidden" name="musicId" id="musicId">
        <div class="modal-body">
            <div class="form-group mb-3">
                <input type="text" class="form-control" name="music" id="music" placeholder="Music" required>
            </div>           
            <div class="form-group mb-3">
                <input type="text" class="form-control" name="creator" id="creator" placeholder="Creator" required>
            </div>           
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary" id="saveBtn">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="d-flex justify-content-between mb-3">
    <h1>PlayList</h1>

    <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addPlaylistModal" id="createButton">
        Add Playlist
    </button>
</div>

<div class="card shadow-lg">
    <div class="card-header bg-primary text-white text-center fs-4">
        Your Playlists
    </div>
    <div class="card-body">
        <table id="playlistsTable" class="display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Playlist</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>

<script src="js/actions/playlist.js"></script>
<?php 
include("app/footer.php")
?>
