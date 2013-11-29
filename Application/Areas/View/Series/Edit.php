<?php
namespace View;

class Edit extends \MainView {

    function HeadContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function Javascript()
    { /*******************************************************/ ?>

<script>
  $(function() {

    $("#series-form").submit(function(e) {
      e.preventDefault();
      $progress = $(this).find(".progress");
      $progress.addClass("active progress-striped");
      $bar = $progress.find(".progress-bar");
      $bar.removeClass("progress-bar-success");
      $bar.addClass("no-transition");
      $bar.attr("aria-valeunow", "0");
      $bar.css("width", "0%");
      setTimeout(function() {
        $bar.removeClass("no-transition");
        $bar.attr("aria-valeunow", "50");
        $bar.css("width", "50%");
      }, 1);
      $glyphicon = $(this).find(".glyphicon");
      $button = $(this).find("button");
      $button.button("loading");
      $.post("/series/save/<?= $this->Model->id ?>/", $(this).serialize())
      .done(function() {
        $bar.addClass("progress-bar-success");
        $bar.removeClass("progress-bar-danger");
        $glyphicon.removeClass();
        $glyphicon.addClass("glyphicon glyphicon-floppy-saved")
      }).fail(function(data) {
        $bar.removeClass("progress-bar-success");
        $bar.addClass("progress-bar-danger");
        $glyphicon.removeClass();
        $glyphicon.addClass("glyphicon glyphicon-floppy-remove");
        $("#error-container").html(_.template($("#error-template").html(), {error:data.responseJSON}));
      }).always(function() {
        $button.button("reset");
        $progress.removeClass("active progress-striped");
        $bar.attr("aria-valeunow", "100");
        $bar.css("width", "100%");
      });
    });
  });
</script>

<script id="error-template" type="text/template">
  <div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Error:</strong> <%= error %>
  </div>
</script>

    <?php /*******************************************************/ }

    function HeaderContent()
    { /*******************************************************/ ?>

    <h1 class="pull-left">Edit series</h1>
    <div class="facebook-profile pull-right">
      <?php if (\FacebookManager::FacebookUser()): ?>
        <div class="thumbnail">
          <fb:profile-pic uid="<?= \FacebookManager::FacebookUser() ?>" width="40" height="40" class="profile-pic pull-left"></fb:profile-pic>
          <div class="profile-details pull-left">
            <p class="fb-name"><fb:name uid="<?= \FacebookManager::FacebookUser() ?>" useyou="false" linked="false"></fb:name></p>
            <p>Welcome back!</p>
          </div>
        </div>
      <?php else: ?>
        <fb:login-button></fb:login-button>
      <?php endif ?>
      <div id="fb-root"></div>
    </div>

    <?php /*******************************************************/ }

    function LeftColumn()
    { /*******************************************************/ ?>

    

    <?php /*******************************************************/ }

    function CenterColumn()
    { /*******************************************************/ ?>

    <div class="panel panel-default clearfix series-create">
      <div class="panel-heading">Adjust the settings for this series entry.</div>
      <div class="panel-body">
        <form id="series-form" method="post" role="form">
          <div class="row">
            <div id="error-container" class="col-lg-12"></div>
            <div class="col-lg-5">
              <div class="form-group description-group">
                <label for="Overview"><h4>Description <small>Enter some short introduction to the series. Generally around 200 characters.</small></h4></label>
                <textarea name="Overview" class="form-control" rows="6"><?= $this->Model->Overview ?></textarea>
              </div>
            </div>
            <div class="col-lg-7">
              <div class="form-group input-group-lg">
                <label for="SeriesName"><h4>Title <small>What the series is called.</small></h4></label>
                <input type="text" name="SeriesName" class="form-control input-group-lg" value="<?= $this->Model->SeriesName ?>">
              </div>
              <div class="row">
                <div class="col-lg-6">
                  <div class="form-group release-time-group">
                    <label for="Airs_Time"><h4>Release time <small>The time on the day when the series are generally being released.</small></h4></label>
                    <div class="input-group input-group-lg">
                      <input type="time" name="Airs_Time" placeholder="HH:mm" class="form-control" value="<?= $this->Model->Airs_Time ?>" />
                    </div>
                  </div>
                </div>
                <div class="col-lg-6">
                  <div class="form-group release-week-day-group">
                    <label for="Airs_DayOfWeek"><h4>Release day <small>The day of the week, on which the series is generally being released.</small></h4></label>
                    <div class="input-group input-group-lg">
                      <select name="Airs_DayOfWeek" class="form-control">
                        <option value="1" <?= $this->Model->Airs_DayOfWeek == 1 ? "selected" : "" ?>>Monday</option>
                        <option value="2" <?= $this->Model->Airs_DayOfWeek == 2 ? "selected" : "" ?>>Tuesday</option>
                        <option value="3" <?= $this->Model->Airs_DayOfWeek == 3 ? "selected" : "" ?>>Wednesday</option>
                        <option value="4" <?= $this->Model->Airs_DayOfWeek == 4 ? "selected" : "" ?>>Thursday</option>
                        <option value="5" <?= $this->Model->Airs_DayOfWeek == 5 ? "selected" : "" ?>>Friday</option>
                        <option value="6" <?= $this->Model->Airs_DayOfWeek == 6 ? "selected" : "" ?>>Saturday</option>
                        <option value="0" <?= $this->Model->Airs_DayOfWeek == 0 ? "selected" : "" ?>>Sunday</option>
                      </select>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-lg-5">
              <div class="well save-group">
                <div class="input-group">
                  <button type="submit" name="submit" data-loading-text="Saving..." class="btn btn-primary btn-lg">Save</button>
                  <span class="input-group-addon">
                    <span class="glyphicon glyphicon-floppy-disk"></span>
                  </span>
                </div>
                <div class="progress progress-striped active">
                  <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </form>
      </div>
    </div>

    <?php /*******************************************************/ }

    function RightColumn()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function FooterContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }
}

?>
