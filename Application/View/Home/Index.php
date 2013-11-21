<?php
class Index implements iThreeColumnMaster {
    private $Model;

    function __construct($model) {
        $this->Model = $model;
    }

    function HeadContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function Javascript()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function HeaderContent()
    { /*******************************************************/ ?>

    

    <?php /*******************************************************/ }

    function LeftColumn()
    { /*******************************************************/ ?>

    

    <?php /*******************************************************/ }

    function CenterColumn()
    { /*******************************************************/ ?>

    <div class="page-header clearfix">
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
    </div> 
    
    <?php foreach ($this->Model->Calendar as $day): ?>
    <div class="panel panel-default thecalendar">
      <div class="panel-heading <?= $day->class ?>"><span class="depth" title="<?= $day->name ?>"><?= $day->name ?></span></div>
      <table class="table table-bordered">
        <tbody>
        <?php foreach ($day->series as $serie): ?>
          <tr>
            <td class="col-md-2"><?= $serie->release_time ?></td>
            <td class="col-md-10"><?= $serie->name ?></td>
          </tr>
        <?php endforeach ?>
        </tbody>
      </table>
    </div>
    <?php endforeach ?> 

    <?php /*******************************************************/ }

    function RightColumn()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function FooterContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }
}

?>
