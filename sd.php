<?php
if (!isset($_SESSION)) {
    session_start();
}
$_SESSION['astr'] = "index";

ob_start();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title></title>
</head>
<body>
<?php
?>
<div id="prv-billboard"></div>
</body>
</html>
