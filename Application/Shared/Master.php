<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Subwai's series calendar</title>

  <link rel="stylesheet" href="/Assets/Css/bootstrap.min.css">
  <link rel="stylesheet" href="/Assets/Css/jquery.mCustomScrollbar.css">
  <link rel="stylesheet" href="/Assets/Css/main.css">
</head>
<body>
  <div class="site-wrapper">
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
    
    <div class="page-wrapper">
      <div class="container page <?= strtolower($this->ControllerName." ".$this->ViewName) ?>">
        <section class="page-header">
          <?= $this->View->HeaderContent(); ?>
        </section>
        <div class="body-wrapper">
          <section class="page-body">
          <?php if (FacebookManager::FacebookUser()): ?>
            <?= $this->View->CenterColumn(); ?>
          <?php else: ?>
            <div class="jumbotron">
              <h1>Welcome, friend!</h1>
              <h5>This website is designed with the sole purpose of giving you easy access to knowledge of when your favourite shows airs.</h5>
              <p>Please log in with facebook in order to continue</p>
              <p><fb:login-button size="xlarge"></fb:login-button></p>
            </div>
          <?php endif ?>
          </section>
          </div>
        </div>
      </div>
    </div>
  </div>

  <footer>
    <div class="container">
      <div class="row menu">
        <ul>
          <li><a href="/"><span>Upcoming episodes</span></a></li>
          <li>|</li>
          <li><a href="/"><span>All shows</span></a></li>
          <li>|</li>
          <li><a href="#about"><span>About</span></a></li>
          <li>|</li>
          <li><a href="#contact"><span>Contact</span></a></li>
        </ul>
      </div>
      <div class="row">
        <p>Copyright ©2013 <b>Series Calendar</b></p>
        <p>Created by <a href="mailto:adam@lyren.nu">Adam Lyrén</a></p>
      </div>
    </div>
  </footer>

  <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
  <script src="/Assets/Javascript/bootstrap.min.js"></script>
  <script src="/Assets/Javascript/underscore-min.js"></script>
  <script src="/Assets/Javascript/jquery.mCustomScrollbar.concat.min.js"></script>
  <?= $this->View->Javascript(); ?>
  <script>
$(function() {
  $pageBody = $(".page-body");
  $pageBody.mCustomScrollbar({
    theme:"dark",
    scrollInertia:150
  });

  $mCSB_container = $(".mCSB_container");
  $content = $mCSB_container.first();
  window.UpdateContainerHeight = function() {
    $mCSB_container.css("height", function() {
      return Math.max($pageBody.height(), $content[0].scrollHeight) == $pageBody.height() ? $pageBody.height() : "auto";
    });
    $pageBody.mCustomScrollbar("update");
  };
  window.UpdateContainerHeight();
  $(window).resize(window.UpdateContainerHeight);

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
