<?php
class Index extends MainView {

    function HeadContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function Javascript()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function HeaderContent()
    { /*******************************************************/ ?>

    <h1 class="pull-left">My series</h1>
    <div class="facebook-profile pull-right">
      <?php if (FacebookManager::FacebookUser()): ?>
        <div class="thumbnail">
          <fb:profile-pic uid="<?= FacebookManager::FacebookUser() ?>" width="40" height="40" class="profile-pic pull-left"></fb:profile-pic>
          <div class="profile-details pull-left">
            <p class="fb-name"><fb:name uid="<?= FacebookManager::FacebookUser() ?>" useyou="false" linked="false"></fb:name></p>
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
  
    <?php if (!$this->Model->Count): ?>
    <div class="alert alert-info">
      <p>You are currently not following any series, <a href="/edit-calendar/" class="alert-link">click here</a> in order to add a few!</p>
    </div>
    <?php endif ?>
    <div class="panel panel-calendar">
      <div class="panel-heading">
        <ul class="nav nav-tabs">
        <?php foreach ($this->Model->Calendar as $day): ?>
          <li class="<?= $day->class." ".$day->active ?>"><a href="#<?= $day->name ?>" data-toggle="tab"><?= $day->name ?></a></li>
        <?php endforeach ?>
        </ul>
      </div>
      <div class="panel-body tab-content">
      <?php foreach ($this->Model->Calendar as $day): ?>
        <div class="tab-pane fade in <?= $day->active ?>" id="<?= $day->name ?>">
          <div class="panel panel-default">
            <div class="panel-heading"><span class="depth" title="<?= $day->name ?>"><?= $day->name ?></span></div>
            <table class="table table-bordered">
              <tbody>
              <?php foreach ($day->series as $serie): ?>
                <tr>
                  <td class="col-md-2"><?= $serie->Airs_Time ?></td>
                  <td class="col-md-10"><?= $serie->SeriesName ?></td>
                </tr>
              <?php endforeach ?>
              </tbody>
            </table>
          </div>
        </div>
      <?php endforeach ?>
      </div>
      <div class="panel-body">
        <div class="panel panel-upcoming">
          <div class="panel-heading"><span class="depth" title="Upcoming (Next 12 hours)">Upcoming (Next 12 hours)</span></div>
          <?php if (empty($this->Model->Upcoming)): ?>
          <div class="alert">
            <strong>Sorry!</strong> There are not going to be any new releases in the near future!
          </div>
          <?php else: ?>
          <table class="table table-bordered">
            <tbody>
            <?php foreach ($this->Model->Upcoming as $serie): ?>
              <tr>
                <td class="col-md-2"><?= $serie->Airs_Time ?></td>
                <td class="col-md-10"><?= $serie->SeriesName ?></td>
              </tr>
            <?php endforeach ?>
            </tbody>
          </table>
          <?php endif ?>
        </div>
      </div>
      <div class="panel-body">
        <div class="panel panel-released">
          <div class="panel-heading"><span class="depth" title="New releases (Last 24 hours)">New releases (Last 24 hours)</span></div>
          <?php if (empty($this->Model->Released)): ?>
          <div class="alert">
            <strong>Sorry!</strong> No new releases right now, check back later!
          </div>
          <?php else: ?>
          <table class="table table-bordered">
            <tbody>
            <?php foreach ($this->Model->Released as $serie): ?>
              <tr>
                <td class="col-md-2"><?= $serie->Airs_Time ?></td>
                <td class="col-md-10"><?= $serie->SeriesName ?></td>
              </tr>
            <?php endforeach ?>
            </tbody>
          </table>
          <?php endif ?>
        </div>
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
