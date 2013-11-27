<?php
class Index extends MainView {

    function HeadContent()
    { /*******************************************************/ ?>



    <?php /*******************************************************/ }

    function Javascript()
    { /*******************************************************/ ?>

<script>
  $(function() {

    $selected = $("#selected-form select");
    $seltable = $("#selected-series-table");
    $restable = $("#search-results-table");

    window.searchResults = [];
    window.selectedList = [];
    <?php foreach ($this->Model as $serie): ?>
      window.selectedList.push({ id:<?= $serie->id ?>, SeriesName:"<?= $serie->SeriesName ?>" });
    <?php endforeach ?>

    var sortList = function(a, b) {
      return a.SeriesName.localeCompare(b.SeriesName);
    };

    var deleteSerie = function() {
      var id = $(this).attr("value");
      window.selectedList = _.indexBy(window.selectedList, "id");
      delete window.selectedList[id];
      window.selectedList = _.values(window.selectedList);
      $seltable.find("tr[value='"+id+"']").remove();
      $selected.find("option[value='"+id+"']").remove();
    };

    var addItem = function() {
      var id = $(this).attr("value");
      window.selectedList.push(_.findWhere(window.searchResults, {id: id}));
      window.selectedList = window.selectedList.sort(sortList);
      $selected.html(_.template($("#select-list-template").html(), {items:window.selectedList}));
      $seltable.html(_.template($("#select-table-template").html(), {items:window.selectedList}));
      $seltable.find("button").click(deleteSerie);
      $(this).attr("disabled","disabled");
    };

    $seltable.find("button").click(deleteSerie);

    $("#search-form").submit(function(e) {
      e.preventDefault();
      $.post("/edit-calendar/search/", $(this).serialize(), function(data) {
        window.searchResults = data;
        $restable.html(_.template($("#search-table-template").html(), {items:window.searchResults}));
        $restable.find("button").click(addItem);
      });
    });

    $("#selected-form").submit(function(e) {
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
      $.post("/edit-calendar/save/", $(this).serialize())
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

<script id="search-table-template" type="text/template">
<tbody>
<% var max_relevance = 0; %>
<% _.each(items, function(item,key,list) { %>
  <%
    if (max_relevance < item.relevance) { max_relevance = item.relevance; }
    item.relevance = (item.relevance/max_relevance)*100;
  %>
  <tr value="<%= item.id %>">
    <td class="col-lg-1 table-group-addon">
      <div class="td-content">
      <% if ($("select[name='selected_series[]'] option[value='"+item.id+"']").length > 0) { %>
        <button class="btn btn-default" type="button" disabled><span class="glyphicon glyphicon-arrow-left"></span></button>
      <% } else { %>
        <button class="btn btn-default" type="button" value="<%= item.id %>"><span class="glyphicon glyphicon-arrow-left"></span></button>
      <% } %>
      </div>
    </td>
    <td><%= item.SeriesName %></td>
    <td class="col-lg-3">
      <div class="progress">
        <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="<%= item.relevance %>" aria-valuemin="0" aria-valuemax="100" style="width: <%= item.relevance %>%">
        </div>
      </div>
    </td>
  </tr>
<% }) %>
</tbody>
</script>

<script id="select-table-template" type="text/template">
<tbody>
<% _.each(items, function(item,key,list) { %>
  <tr value="<%= item.id %>">
    <td><%= item.SeriesName %></td>
    <td class="col-lg-1 table-group-addon">
      <div class="td-content">
        <button class="btn btn-default" type="button" value="<%= item.id %>"><span class="glyphicon glyphicon-remove"></span></button>
      </div>
    </td>
  </tr>
<% }) %>
</tbody>
</script>

<script id="select-list-template" type="text/template">
<% _.each(items, function(item,key,list) { %>
  <option value="<%= item.id %>" selected></option>
<% }) %>
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

    <h1 class="pull-left">Edit your calendar</h1>
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

    <div class="panel panel-default clearfix edit-calendar">
      <div class="panel-heading">Search for series and add them to your list, or add your own custom series.</div>
      <div class="panel-body">
        <div class="row">
          <div id="error-container" class="col-lg-12"></div>
          <div class="col-lg-5">
            <table id="selected-series-table" class="table table-bordered">
              <tbody>
              <?php foreach ($this->Model as $serie): ?>
                <tr value="<?= $serie->id ?>">
                  <td><div class="td-content"><?= $serie->SeriesName ?></div></td>
                  <td class="col-lg-1 table-group-addon">
                    <div class="td-content">
                      <button class="btn btn-default" type="button" value="<?= $serie->id ?>"><span class="glyphicon glyphicon-remove"></span></button>
                    </div>
                  </td>
                </tr>
              <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <div class="col-lg-7">
            <div class="well add-series">
              <div class="media">
                <a href="/series/create" class="btn btn-primary pull-left">Create series</a>
                <div class="media-body">
                  <p>Create your own custom series entry.</p>
                </div>
              </div>
            </div>
            <form id="search-form" role="form">
              <div class="input-group input-group-lg">
                <input class="form-control" name="search_text" type="text">
                <span class="input-group-btn">
                  <button class="btn btn-default" name="search_button" type="submit">Search</button>
                </span>
              </div>
            </form>
            <div class="panel panel-default search-results">
              <div class="panel-heading">Chose from the search results below and addthem to your series calendar.</div>
              <table id="search-results-table" class="table table-bordered">
              </table>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-lg-5">
            <form id="selected-form" role="form">
              <select class="col-lg-12" name="selected_series[]" multiple hidden>
                <?php foreach ($this->Model as $serie): ?>
                  <option value="<?= $serie->id ?>" selected><?= $serie->SeriesName ?></option>
                <?php endforeach ?>
              </select>
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
            </form>
          </div>
        </div>
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
