<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('common/header'); 
?>
<body>
	<div class="container-fluid error">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<div class="panel panel-danger">
					<div class="panel-heading">
						<span class="glyphicon glyphicon-warning-sign"></span>
						<?php echo $message; ?>					
					</div>
				  	<div class="panel-body" id="panel_body">
				  		<?php if($redirect){?>
							<a href="<?php echo site_url($redirect); ?>" class="btn btn-primary">Regresar</a>
						<?php } ?>
				  	</div>
				</div>
			</div>			
		</div>		
	</div>	
</body>
<!-- Common Javascript -->
<?php $this->load->view('common/js'); ?>
<script type="text/javascript">
	var panel_body = document.querySelector("#panel_body");
	panel_body.style.display = "none";
	<?php if($redirect){ ?>
	setTimeout(function() {
		window.location.href = '<?php echo site_url($redirect); ?>';		
	}, 3000);
	<?php } ?>
</script>
</html>