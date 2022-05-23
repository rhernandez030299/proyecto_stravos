<head>	
	<meta charset="UTF-8">	
	<!-- Compatibiliad con IE y Chrome-->
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="author" content="Proyecto" />
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
	<!-- Indica a los buscadores el seguimiento de robots.txt-->
    <meta name="robots" content="INDEX,FOLLOW" />
	<title><?php echo $title ?></title>
	<?php if ( ! empty( $icon ) && is_string( $icon ) ){ ?>
	<link href="<?php echo $icon ?>" type="image/x-icon">
	<?php } ?>
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->    	
	<?php foreach ($css as $css_href){ ?>
	<link href="<?php echo $css_href; ?>" rel="stylesheet">
	<?php } ?>
	<link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" rel="stylesheet">
	<?php if( ! empty($jsvars) ){ ?> 	
	<script>
	<?php foreach ($jsvars as $name => $value){ ?>

		<?php if ( is_json($value) || is_numeric($value) ) { ?>	
		var <?php echo "$" . $name; ?> = <?php echo $value ?>;
		<?php }else if ( is_array($value) ){ ?>
			var <?php echo "$" . $name; ?> = <?php echo json_encode($value ); ?>;		
		<?php }else{ ?>
		var <?php echo "$" . $name; ?> = "<?php echo $value ?>";
		<?php } ?>
	<?php } ?>
	</script>
	<?php } ?>
</head>

