<?php
$APIContext = stream_context_create(array(
    'http' => array(
      'method'  => 'POST',
      'header'  => "Content-type: text/html\r\n",
      'content' => http_build_query(array('email' => 'user@email.com'))
    ),
  ));
$return = APICall();

?>
<!DOCTYPE html>

<html>

 <head>

  <title> Test Javascript API </title>
<script>
$.post('APICall()', {email: 'user@email.com'}, function(data) {
  alert(data);
});
</script>
  </head>

 <body>

<form action="APICall()" method="post">
  <input type="text" name="email" value="user@email.com" />
  <input type="submit" value="submit" />
</form>
</body>

</html>
