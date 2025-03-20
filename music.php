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
        <div class="modal-body">
            <div class="form-group">
                <input type="text" class="form-control" name="music" id="music" placeholder="Music" required>
            </div>           
            <div class="form-group">
                <input type="text" class="form-control" name="creator" id="creator" placeholder="Creator" required>
            </div>           

        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<div class="d-flex justify-content-between mb-3">
<h1>Music</h1>

<button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addMusicModal">
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