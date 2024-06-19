<?ob_start();//start output buffering, to handle headers properly?>
<!doctype html>
<html lang="en" data-bs-theme="auto">
<? Session::loadTemplate("_head"); ?>
<body>
  <? Session::loadTemplate("_DarkLightMode"); ?>
  <main><?

  //loads the template of currently executing script dynamically
  Session::loadTemplate(Session::currentScript());
  ?>
  </main>
  <script src="/login/assets/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?ob_end_flush();//flushes output buffer, handles headers properly?>