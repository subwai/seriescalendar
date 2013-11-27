<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subwai's series calendar</title>

  <link rel="stylesheet" href="/Assets/Css/bootstrap.min.css">
  <link rel="stylesheet" href="/Assets/Css/main.css">
</head>
<body>
  <nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container">
      <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
          <span class="sr-only">Toggle navigation</span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
          <span class="icon-bar"></span>
        </button>
        <a class="navbar-brand" href="/">Subwai's series calendar</a>
      </div>

      <div class="collapse navbar-collapse navbar-ex1-collapse">
        <ul class="nav navbar-nav">
          <li><a href="/edit-calendar">Edit Calendar</a></li>
          <li><a href="/series">Your custom series</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <div class="container <?= strtolower($this->ControllerName." ".$this->ViewName) ?>">
    <section class="page-header">
      <?= $this->View->HeaderContent(); ?>
    </section>
    <section class="page-body">
      <?= $this->View->CenterColumn(); ?>
    </section>
  </div>

  <footer>
  </footer>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="/Assets/Javascript/bootstrap.min.js"></script>
  <script src="/Assets/Javascript/underscore-min.js"></script>
  <?= $this->View->Javascript(); ?>
  <script>
$(function() {
  $.ajaxSetup({ cache: true });
  $.getScript('//connect.facebook.net/en_US/all.js', function(){

      FB.init({
        appId: '<?= FacebookManager::FacebookInstance()->getAppId() ?>',
        cookie: true,
        xfbml: true,
        oauth: true
      });
      FB.Event.subscribe('auth.login', function(response) {
        window.location.reload();
      });
      FB.Event.subscribe('auth.logout', function(response) {
        window.location.reload();
      });
  });
});
</script>
</body>
</html>
