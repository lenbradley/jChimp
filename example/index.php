<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>jChimp</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    <script src="../scripts/jquery.jchimp.js"></script>
    <script>
    jQuery(document).ready( function($) {
        $('#jchimp').jChimp( 'http://{path}/includes/jchimp.ajax.php' );
    });
    </script>
</head>
<body>

<form method="POST" action="" id="jchimp">
    <input type="text" name="full_name" placeholder="Full Name"><br>
    <input type="text" name="email_address" placeholder="Email Address">
    <input type="submit" value="Sign Up">
    <input type="hidden" name="list_name" value="Newsletter">
</form>
    
</body>
</html>