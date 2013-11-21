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

<script>
  $(function() {
    $("form[name='search_form']").submit(function(e) {
      e.preventDefault();
      $.post("/edit-calendar/search/", $(this).serialize(), function(data) {
        $table = $("#search-results-table");
        for (var i = 0; i < data.length; i++) {
          $table.find('tbody')
            .append($('<tr>')
              .append($('<td>')
                .text(data[i].name)
            ));
        };
      });
    });
  });
</script>

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
      <h1 class="pull-left">Edit my calendar</h1>
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

    <div class="panel panel-default clearfix">
      <div class="panel-heading">Search for series and add them to your list, or add your own custom series.</div>
      <div class="panel-body">
        <div class="row">
          <div class="col-lg-5">
            <select class="col-lg-12" name="selected_series" size="15" multiple>
              <?php foreach ($this->Model as $serie): ?>
                <option><?= $serie->name ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="col-lg-7">
            <form name="search_form">
              <div class="input-group input-group-lg">
                <input class="form-control" name="search_text" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" name="search_button" type="submit">Search</button>
                </span>
              </div>
            </form>
            <div class="panel panel-default search-results">
              <div class="panel-heading">Chose from the search results below and addthem to your series calendar.</div>
              <table id="search-results-table" class="table">
                <tbody></tbody>
              </table>
            </div>
          </div>
        </div>
        <div class="panel-body">
          <button type="button" class="btn btn-primary btn-lg col-lg-1">Save</button>
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
