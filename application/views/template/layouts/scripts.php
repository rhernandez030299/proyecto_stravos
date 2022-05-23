<script type="text/javascript">
	var base_url = "<?php echo $this->config->item('base_url'); ?>";
</script>

<?php foreach ($js as $js_src): ?>
<script type="text/javascript" src="<?php echo $js_src; ?>"></script>
<?php endforeach ?>


<?php if( ! empty($this->session->IDFORMULARIO)){ ?> 

<div class="modal fade" id="modalformulario" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
		<div class="modal-dialog" role="document">
				<div class="modal-content">
						<div class="modal-header">
								<h5 class="modal-title">Tiene un formulario pendiente por responder.</h5>
								<button type="button" class="close" data-dismiss="modal" aria-label="Close">
										<i aria-hidden="true" class="ki ki-close"></i>
								</button>
						</div>
						<div class="modal-footer">
								<a href="<?php echo base_url('formularios/ver_formulario/'.$this->session->IDFORMULARIO); ?>">Ir al formulario</a>
						</div>
				</div>
		</div>
	</div>

	<script>
		$("#modalformulario").modal("show");
	</script>
	</div>
<?php }  ?>