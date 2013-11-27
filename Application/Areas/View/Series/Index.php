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

    <h1 class="pull-left">Your custom created series</h1>
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

    <div class="panel panel-default clearfix series-index">
      <div class="panel-heading clearfix">This is a list of your custom made series, which others can subscribe to.<a href="/series/create" class="btn btn-primary btn-sm pull-right">Create more</a></div>
      <div class="panel-body">
        <table class="table table-default table-bordered">
          <thead>
            <th class="edit-column"></th>
            <th class="title-column">Title</th>
            <th class="description-column">Description</th>
            <th class="release-time-column">Time</th>
            <th class="release-week-day-column">Day</th>
          </thead>
          <tbody>
          <?php foreach($this->Model->series as $series): ?>
            <tr>
              <td class="col-lg-1 table-group-addon">
                <div class="td-content">
                  <a href="/series/edit/<?= $series->id ?>/" class="btn btn-default pull-left"><span class="glyphicon glyphicon-edit"></span></a>
                  <a href="#subscribed" class="btn btn-default pull-left subscribed" value="<?= $series->id ?>"><span class="glyphicon glyphicon-heart"></span></a>
                </div>
              </td>
              <td class="col-lg-2"><div class="td-content"><?= $series->SeriesName ?></div></td>
              <td class="col-lg-6"><div class="td-content"><?= $series->Overview ?></div></td>
              <td class="col-lg-1"><div class="td-content"><?= $series->Airs_Time ?></div></td>
              <td class="col-lg-1"><div class="td-content"><?= $this->Model->days[$series->Airs_DayOfWeek] ?></div></td>
            </tr>
          <?php endforeach ?>
          </tbody>
        </table>
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
