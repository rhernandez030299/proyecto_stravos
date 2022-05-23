<?php $CI =&get_instance(); ?>
<div class="row">

<?php if (empty($consultar_proyectos)): ?>
	<div class="col-md-12 sin-proyecto text-center">
		No tienes proyectos asignados
	</div>
<?php endif ?>


<?php foreach ($consultar_proyectos as $proyecto): ?>

	<?php 

		$data_miembro_proyecto = array("usuario_creacion" => 1, "idproyecto" => $proyecto->idproyecto);
		$consultar_miembro_profesor = $CI->Proyectos_model->consultar_miembro_profesor_by_data($data_miembro_proyecto);
		$nombre_profesor = "";
		$ruta_imagen = "";
		foreach ($consultar_miembro_profesor as $miembro_profesor) {
			$consultar_usuario = $CI->Usuarios_model->consultar_by_id($miembro_profesor->idusuario);
			if( ! empty($consultar_usuario)){
				$nombre_profesor = $consultar_usuario->nombre . " " . $consultar_usuario->apellido;
				$ruta_imagen = $consultar_usuario->ruta_imagen;
			}
		}

		$data_archivo = array("idproyecto" => $proyecto->idproyecto);
		$consultar_archivo = $CI->Proyectos_model->consultar_archivo_proyecto_by_data($data_archivo);
	?>	

	<div class="col-12 col-sm-6 col-xs-12 col-md-4 col-lg-4 mb-5">
		<div class="contact">
	    <main>
	      <section>
	        <div class="content-card">
	        	
		        <img src="<?php echo ( ! empty($ruta_imagen)) ? base_url('uploads/'.$ruta_imagen) : $pub_url . 'img/imagen3.jpg'; ?>" alt="Profile Image">

	          	<aside>
	          		<p class="profesor"><?php echo $proyecto->nombre; ?></p>
	            	<p><?php echo $nombre_profesor; ?></p>
	          	</aside>
	          	<aside class="aside-metodologia">

	          		<?php $consultar_metodologia = $CI->Proyectos_model->consultar_proyecto_metodologia_by_id_proyecto($proyecto->idproyecto); ?>

	          		<?php foreach ($consultar_metodologia as $metodologia): ?>
	          			<a href="<?php echo base_url('trabajos/fases/'.$proyecto->url.'/'.$metodologia->url); ?>">
	          				<span><?php echo $metodologia->nombre; ?></span>
	          			</a>
	          		<?php endforeach ?>
	            </aside>

	            <aside class="aside-miembro">
          			<a href="<?php echo base_url('trabajos/miembros/'.$proyecto->url.'/'.$metodologia->url); ?>">
          				<span>Miembros</span>
          			</a>
	            </aside>
		    		
	    		<?php if( ! empty($consultar_archivo)){ ?>      
			        <button>
			        	<span>Archivos</span>
			            <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" viewBox="0 0 48 48"> <g class="nc-icon-wrapper" fill="#444444"> <path d="M14.83 30.83L24 21.66l9.17 9.17L36 28 24 16 12 28z"></path> </g> </svg>
			        </button>
		        <?php } ?>
	        </div>

	        <?php if( ! empty($consultar_archivo)){ ?>
		        <div class="title"><p>Ver archivos adjuntos</p></div>
		    <?php } ?>
	      </section>
	    </main>

	    <?php foreach ($consultar_archivo as $archivo): ?>
	    	<?php 
	    		$url = base_url() . 'uploads/' . $archivo->ruta;
	    		$ext = explode(".", $archivo->ruta);
	    		$ext = array_pop($ext);
	    		
                if ($ext == "pdf") {
                    $url = base_url() .  "assets/public/img/pdf.jpg";
                } else if (strpos($ext, "doc") !== false) {
                    $url = base_url() .  "assets/public/img/word.png";
                } else if (strpos($ext, "xls") !== false) {
                    $url = base_url() .  "assets/public/img/excel.png";
                }
	    	?>
	    	
	    	<nav>
	    		<a href="<?php echo base_url('trabajos/descargas/'.$archivo->ruta); ?>">
		    		<div class="contenedor-archivos">
		    			<div class="foto">
		    				<div class="icon">
					          	<img src="<?php echo $url; ?>">
					        </div>
		    			</div>
		    			<div class="texto">
		    				<div class="content-card">
					          <p><?php echo $archivo->client_name; ?></p>
					        </div>
					        
					        <span class="fa fa-download">
					        	
					        </span>
		    			</div>
		    		</div>
	    		</a>
			
		    </nav>
		<?php endforeach ?>
	</div>
	</div>
<?php endforeach ?>

</div>