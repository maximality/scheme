<!DOCTYPE HTML>
<html lang="en-US">
<head>
	<meta charset="UTF-8">
	<title>Система управления <?php echo $this->settings->site_title; ?></title>
	
	<!--[if lt IE 9]><script src="<?php echo $dir_js;?>html5shiv.js"></script><![endif]-->
	
	<link rel="stylesheet" type="text/css" href="<?php echo $dir_css;?>jquery-ui-1.10.0.custom.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $dir_css;?>jquery.multiselect.css" media="all"/>
	<!-- Main CSS file -->
	<link rel="stylesheet" type="text/css" href="<?php echo $dir_css;?>main.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $dir_js;?>autocomplete/styles.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="<?php echo SITE_URL;?>templates/css/styles_edit.css" media="all"/>
	<link rel="stylesheet" type="text/css" href="<?php echo $dir_css;?>floor.css" media="all"/>
        
	<script src="<?php echo $dir_js;?>jquery-1.9.0.min.js"></script>
	<script src="<?php echo $dir_js;?>jquery.history.js"></script>
	<script src="<?php echo $dir_js;?>jquery-ui-1.10.0.custom.min.js"></script>
	<script src="<?php echo $dir_js;?>jquery.multiselect.min.js"></script>
	<script src="<?php echo $dir_js;?>jquery.tablesorter.min.js"></script>
	<script src="<?php echo $dir_js;?>jquery.jcarousel.min.js"></script>
    <script src="<?php echo $dir_js;?>jquery.form.js"></script> 
    <script src="<?php echo $dir_js;?>jquery.ui.touch-punch.js"></script> 
    <script src="<?php echo $dir_js;?>jquery.mjs.nestedSortable.js"></script> 
    <script src="<?php echo $dir_js;?>plupload/plupload.js"></script> 
    <script src="<?php echo $dir_js;?>plupload/plupload.flash.js"></script> 
    <script src="<?php echo $dir_js;?>plupload/plupload.html5.js"></script> 
    <script src="<?php echo $dir_js;?>plupload/plupload.html4.js"></script> 
    <script src="<?php echo $dir_js;?>plupload/i18n/ru.js"></script> 
    <script src="<?php echo $dir_js;?>autocomplete/jquery.autocomplete.min.js"></script> 
    <link rel="stylesheet" type="text/css" href="<?php echo $dir_css; ?>imgareaselect-default.css" />
    <script type="text/javascript" src="<?php echo $dir_js; ?>jquery.imgareaselect.pack.js"></script>
    <script>
		var site_url = "<?php echo SITE_URL;?>";
		var admin_url = "<?php echo DIR_ADMIN;?>";
		var dir_images= "<?php echo $dir_images;?>";
	</script>
	<!-- Main JS file -->
	<script src="<?php echo $dir_js;?>main.js"></script>
    <script src="<?php echo DIR_ADMIN; ?>ckeditor/ckeditor.js"></script>
</head>
<body>
	<div id="wrapper">
		<!-- Site header start -->
		<div id="header">
			<div class="fix-width">
				
				<div class="logo">
					<a href="<?php echo DIR_ADMIN; ?>">
						<span class="top">система управления</span>
						<span class="btm"><?php echo $site_host;?></span>
					</a>
				</div>
				<!--
				<form action="<?php echo DIR_ADMIN;?>" class="input search" method="get">
                	<input type="hidden" name="module" value="catalog">
                	<input type="hidden" name="action" value="products">
					<input autocomplete="off" id="quick_input_search" name="name" type="text" data-url="<?php echo DIR_ADMIN;?>?module=search&action=quick" placeholder="Поиск"/>
					<input type="submit" value=""/>
				</form>
				-->
				<ul class="top-menu">
					<li id="user-menu">
						<a href="<?php echo DIR_ADMIN; ?>?module=admins&action=profile" class="ajax_link" data-module="profile">
							<img class="ticon user-w" src="<?php echo $dir_images;?>icon.png" alt="user"/>
							Здравствуйте, <?php echo $admin['name'];?>
						</a>
						<div class="user-btn">
							<div class="menu-popup">
								<ul>
									<li><a href="<?php echo DIR_ADMIN; ?>?module=admins&action=profile" class="ajax_link" data-module="profile">Личные настройки</a></li>
									<li><a href="<?php echo DIR_ADMIN; ?>?logout=1">Выход</a></li>
								</ul>
								<div class="top"></div>
							</div>
						</div>
					</li>
					<!--<li>
						<img class="ticon tool-w" src="<?php echo $dir_images;?>icon.png" alt="user"/>
						<a href="#">Помощь</a>
					</li>-->
					<li id="go_to_site">
						<img class="ticon arrow-w" src="<?php echo $dir_images;?>icon.png" alt="user"/>
						<a href="<?php echo SITE_URL;?>" target="_blank">Перейти на <?php echo $site_host;?></a>
					</li>
				</ul>
                <!--
                    <div id="quick_search_results">
                    	<div class="tooltip-corner"></div>
                        <div class="tooltip-container"></div>
                    </div>				
				-->
				
				<div class="clear"></div>
				
			</div><!-- .fix-width end -->
		</div>
		<!-- Site header end -->
		
		<div id="main" class="fix-width">
			
			<!-- Main site sidebar start -->
			<div id="sidebar">
				
				<!-- Menu start -->
				<div class="menu">
					<?php if($site->admins->get_level_access("buildings")) { ?>
					<div class="menu-item <?php if($module=="buildings") echo "active"; ?>" id="menu-module-buildings">
						<a href="<?php echo DIR_ADMIN; ?>?module=buildings&action=index" class="ajax_link" data-module="buildings">
                          <img class="micon catalog" src="<?php echo $dir_images;?>icon.png" alt="icon"/>
							<span class="clip">Здания</span>
						</a>
						<div class="menu-btn">
							<div class="menu-popup">
								<ul>
									<li><a href="<?php echo DIR_ADMIN; ?>?module=buildings&action=add" class="ajax_link" data-module="buildings">Добавить здание</a></li>
								</ul>
								<div class="top"></div>
							</div>
						</div>
					</div>
                    <?php } ?>
			
                                    <!--
                    <?php if($site->admins->get_level_access("settings")==2) { ?>
					<div class="menu-item <?php if($module=="settings") echo "active"; ?>" id="menu-module-settings">
						<a href="<?php echo DIR_ADMIN; ?>?module=settings&action=index" class="ajax_link" data-module="settings">
                          <img class="micon tools" src="<?php echo $dir_images;?>icon.png" alt="icon"/>
							<span class="clip">Настройки</span>
						</a>
					</div>
                    <?php } ?>
                                    -->
                    
                    <?php if($site->admins->get_level_access("admins")) { ?>
					<div class="menu-item <?php if($module=="admins" and $action!="profile") echo "active"; ?>" id="menu-module-admins">
						<a href="<?php echo DIR_ADMIN; ?>?module=admins&action=index" class="ajax_link" data-module="admins">
							<img class="micon admins" src="<?php echo $dir_images;?>icon.png" alt="icon"/>
							<span class="clip">Администраторы</span>
						</a>
                        <?php if($site->admins->get_level_access("admins")==2) { ?>
						<div class="menu-btn">
							<div class="menu-popup">
								<ul>
									<li><a href="<?php echo DIR_ADMIN; ?>?module=admins&action=add" class="ajax_link" data-module="admins">Добавить админа</a></li>
									<li><a href="<?php echo DIR_ADMIN; ?>?module=admins&action=groups" class="ajax_link" data-module="admins">Группы админов</a></li>
								</ul>
								<div class="top"></div>
							</div>
						</div>
                        <?php } ?>
					</div>
                    <?php if($site->admins->get_level_access("admins")==2) { ?>
                                        <ul class="menu-added <?php if($module=="admins" and $action!="profile") echo "active"; ?>" id="menu-added-module-admins">
						<li><a href="<?php echo DIR_ADMIN; ?>?module=admins" class="ajax_link" data-module="admins">Администраторы</a></li>
						<li><a href="<?php echo DIR_ADMIN; ?>?module=admins&action=groups" class="ajax_link" data-module="admins">Группы админов</a></li>
					</ul>
					<?php }
					}  
					?>

                   <!-- <?php if($site->admins->get_level_access("tools")==2) { ?>
					<div class="menu-item <?php if($module=="tools") echo "active"; ?>" id="menu-module-tools">
						<a href="<?php echo DIR_ADMIN; ?>?module=tools&action=index" class="ajax_link" data-module="tools">
                          <img class="micon tools" src="<?php echo $dir_images;?>icon.png" alt="icon"/>
							<span class="clip">Инструменты</span>
						</a>
					</div>
                    <?php } ?>-->
				</div>
				<!-- Menu end -->
				
			</div>
			<!-- Main site sidebar end -->
			
			<!-- Main site content start -->
			<div id="content">
            	<div id="contentHelper">
				<?php echo $content; ?>
                </div>
                <div class="clear"></div>
            </div>
			<!-- Main site content start -->
			
			<div class="clear"></div>
                        
                      
		</div><!-- #main end -->
		
	</div><!-- #wrapper end -->  
        
        <div class="image-container">
            <div class="ititle">
                <div class="right">
                    <span class="btn standart-size red">
                        <button class="js-close-image-container">
                            <span><img class="bicon cross-w" src="<?php echo $dir_images; ?>icon.png" alt="icon"/>Закончить</span>
                        </button>
                    </span>
                </div>
            </div>
            <div class="icontent">
                <img src="" class="js-i" width="100%" height="95%" alt=""/>
            </div>
        </div>
</body>
</html>