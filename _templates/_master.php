<?ob_start();?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<? Session::loadTemplate("_head"); ?>
<body>
  <? Session::loadTemplate("_DarkLightMode"); ?>
  <main><?
  Session::loadTemplate(Session::currentScript());
  ?>
  </main>
  <script src="/login/assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?ob_end_flush();?>