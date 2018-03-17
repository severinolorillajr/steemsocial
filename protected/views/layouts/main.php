<!DOCTYPE html>
<html prefix="og: http://ogp.me/ns#" xmlns="http://www.w3.org/1999/xhtml" xmlns:fb="http://www.facebook.com/2008/fbml"   >
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta property="al:android:url" content="sharesample://story/1234">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="description" content="">
        <meta name="author" content="">
        <meta property="og:image" content="<?= $this->themeUrl; ?>/images/share.png" />
        <link rel="icon" href="<?= $this->themeUrl; ?>/images/favicon.ico">
        <title>Steem Social</title>

        <!-- Bootstrap core CSS -->        
        <link rel="stylesheet" type="text/css" href="<?= $this->themeUrl; ?>/css/bootstrap.min.css"/>   
        <link rel="stylesheet" type="text/css" href="<?= $this->themeUrl; ?>/css/neases.css"/>   

        <script src="<?= $this->themeUrl; ?>/js/script/jquery-1.11.1.js"></script>
        <script src="<?= $this->themeUrl; ?>/js/script/jquery.easing-1.3.js"></script>
        <script src="<?= $this->themeUrl; ?>/js/script/jquery.mousewheel-3.1.12.js"></script>
        <script src="<?= $this->themeUrl; ?>/js/script/jquery.jcarousellite.js"></script>     
        <script src="<?= $this->themeUrl; ?>/js/enscroll-0.6.0.min.js"></script>              
        <script src="<?= $this->themeUrl; ?>/js/main.js"></script>                     
        <script src="<?= $this->themeUrl; ?>/js/upclick.js"></script>        
        <!-- Bootstrap core JS -->
        <script src="<?= $this->themeUrl; ?>/js/bootstrap.min.js"></script>
    </head>
    <body>
        <?php echo $content; ?>
    </body>
</html>