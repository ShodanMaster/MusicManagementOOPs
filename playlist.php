<?php 
include("app/header.php")
?>

<!-- Playlist Modal -->
<div class="modal fade" id="addPlaylistModal" tabindex="-1" aria-labelledby="addPlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h1 class="modal-title fs-5" id="addPlaylistModalLabel">Add Playlist</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="playlist-form">
        <input type="hidden" name="playlistId" id="playlistId">
        <div class="modal-body">
            <div class="form-group mb-3">
                <input type="text" class="form-control" name="playlist" id="playlist" placeholder="Playlist" required>
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

<!-- View Playlist Modal -->
<div class="modal fade" id="viewPlaylistModal" tabindex="-1" aria-labelledby="viewPlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h1 class="modal-title fs-5" id="viewPlaylistModalLabel">Playlist</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-striped" id="playlistMusicsTable">
          <thead>
            <tr>
              <th>#</th>
              <th>Music</th>
              <th>Creator</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
      </div>
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
