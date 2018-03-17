<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0" />
		<title>CIRCUS Social</title>

		<link href="<?php echo $this->themeUrl; ?>/css/reset.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->themeUrl; ?>/css/ui_custom.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->themeUrl; ?>/css/styles.css" rel="stylesheet" type="text/css" />
		<link href="<?php echo $this->themeUrl; ?>/css/custom_styles.css" rel="stylesheet" type="text/css" />

		<script type="text/javascript" src="<?php echo $this->themeUrl; ?>/js/jquery/jquery.min.js"></script> 
		<script type="text/javascript" src="<?php echo $this->themeUrl; ?>/js/jquery/jquery-ui.min.js"></script>		
		<script type="text/javascript" src="<?php echo $this->themeUrl; ?>/js/plugins/ui/jquery.easytabs.min.js"></script>
	</head>
	<body>
		<div id="pageOverlay"  ></div>   	        

		<!-- Sidebar begins -->
		<div id="sidebar">
		    <div class="mainNav"></div>

		    <!-- Secondary nav -->
		    <div class="secNav">
				<div class="secWrapper">
				    <div class="secTop">
						<div id="topLeftLogo">
							<p><img src="<?php echo $this->themeUrl; ?>/images/logo.png" alt="" width="226" height="122" /></p>
						</div>
						<p style="text-align: center; font-weight: bold;">NEA SES Admin Panel</p>
				    </div>    
				    <!-- Sidebar subnav -->
				</div> 
		    </div>
		</div>
		<!-- Sidebar ends -->   

		<!-- Content begins -->
		<div id="content">
		    <div class="contentTop">
				<span class="pageTitle"><span class="icon-screen"></span><?php echo $this->pageTitle; ?></span>
		    </div>

		    <!-- Breadcrumbs line -->
		    <div class="breadLine">
				<div class="bc">
				    <ul id="breadcrumbs" class="breadcrumbs">                               
						<?php
						if (is_array($this->breadcrumbs)) {
						    foreach ($this->breadcrumbs as $bd) {
								print_r($bd);
						    }
						} ?>
				    </ul> 
				</div>
		    </div>
		    <!-- Main content -->
		    <div class="wrapper">
				<?php echo $content; ?>
		    </div>
		    <!-- Main content ends -->
	    <!-- Content ends -->  
    </body>
</div>