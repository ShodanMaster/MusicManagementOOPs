<?php 
include("app/header.php");
?>

<!-- Music Modal -->
<div class="modal fade" id="addMusicModal" tabindex="-1" aria-labelledby="addMusicModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h1 class="modal-title fs-5" id="addMusicModalLabel">Add Music</h1>
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

<!-- Add to Playlist -->
<div class="modal fade" id="addToPlaylistModal" tabindex="-1" aria-labelledby="addToPlaylistModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-primary text-white">
        <h1 class="modal-title fs-5" id="addToPlaylistModalLabel">Add to Playlist</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="addtoplaylist-form">
        <input type="hidden" name="playlistmusicId" id="playlistmusicId">
        <div class="modal-body">
            <div class="form-group mb-3">
                <select class="form-control" name="playlist" id="playlist" required>
                    <option value="" selected disabled>--select playlist--</option>
                </select>
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
<h1>Music</h1>

<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMusicModal" id="createButton">
    Add Music
</button>
</div>

<div class="card shadow-lg">
    <div class="card-header bg-primary text-white text-center fs-4">
        Your Musics
    </div>
    <div class="card-body">
        <table id="musicsTable" class="display">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Music</th>
                    <th>By</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
<script src="js/actions/music.js"></script>
<?php 
include("app/footer.php");
?>