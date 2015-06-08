<!doctype html>
<html>
    <head>
        <meta charset="utf-8"/>
        <title>Главная</title>
        <link rel="stylesheet" href="<?php echo $dir_css; ?>main.css"/>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
        <link href="<?php echo $dir_css; ?>widgets.css" rel="stylesheet" media="screen">

        <!--style for IE-->
        <!--[if lte IE 8]><link rel="stylesheet" href="<?php echo $dir_css; ?>ie8.css" media="screen, projection"><![endif]-->
        <!--[if lte IE 7]><link rel="stylesheet" href="<?php echo $dir_css; ?>ie7.css" media="screen, projection"><![endif]-->
        
        <script src="<?php echo $dir_js; ?>jquery-ui-1.10.4.custom.min.js"></script>
        <script src="<?php echo $dir_js; ?>widgets.js"></script>
        
        <script type="text/javascript" src="<?php echo $dir_js; ?>main.js"></script>
        
        <script type="text/javascript">
               site_url = "<?php echo SITE_URL; ?>";
               dir_js =  "<?php echo $dir_js; ?>";
               $(function(){
                   var fbGallery = $(".fb-gallery");	
                    if (fbGallery.length) {			
                            fbGallery.fancybox();	
                    }
               })
        </script>
    </head>
    <body>
        <div class="wrapper">
                <?php echo $content; ?>
        </div>
    </body>
</html>
