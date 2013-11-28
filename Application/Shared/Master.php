<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Quartz says - Hello world!</title>
  <link rel="stylesheet" href="/Assets/Css/main.css">
</head>
<body>

  <section class="page-header">
    <?= $this->View->HeaderContent(); ?>
  </section>
  <section class="page-body">
    <?= $this->View->CenterColumn(); ?>
  </section>

  <footer>
    <?= $this->View->FooterContent(); ?>
  </footer>
  
  <?= $this->View->Javascript(); ?>
</body>
</html>
