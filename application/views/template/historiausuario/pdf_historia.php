<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width: 100%; text-align: center; background-color: #e6e6e6; color: #000; padding: 10px; font-weight: bolder; letter-spacing: 1px; font-size: 20px; border: 1px solid #000">
            Historia de usuario
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:50%; text-align:left" class="celdas-titulo">
            <strong>Fecha inicio:</strong>
            &nbsp;
            <?php echo $fecha_ini; ?>
        </td>
        
        <td style=" width:50%; text-align:left" class="celdas-titulo">
            <strong>Fecha finalización:</strong>
            &nbsp;
            <?php echo $fecha_fin; ?>
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:50%; text-align:left" class="celdas-titulo">
            <strong>Número:</strong>
            &nbsp;
            <?php echo $numeracion; ?>
        </td>
        
        <td style=" width:50%; text-align:left" class="celdas-titulo">
            <strong>Responsable:</strong>
            &nbsp;
            <?php echo $responsable; ?>
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:100%; text-align:left" class="celdas-titulo">
            <strong>Título:</strong>
            &nbsp;
            <?php echo $titulo; ?>
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:50%; text-align:left" class="celdas-titulo">
            <strong>Objetivo:</strong>
            &nbsp;
            <?php echo $objetivo; ?>
        </td>
        
        <td style=" width:50%; text-align:left" class="celdas-titulo">
            <strong>Prioridad:</strong>
            &nbsp;
            <?php echo $prioridad; ?>
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:50%; text-align:left" class="celdas-titulo">
            <strong>Riesgo desarrollo:</strong>
            &nbsp;
            <?php echo $riesgodesarrollo; ?>
        </td>
        
        <td style=" width:50%; text-align:left" class="celdas-titulo">
            <strong>Tiempo estimado:</strong>
            &nbsp;
            <?php echo $tiempo_estimado; ?>
        </td>
    </tr>
</table>

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:100%; text-align:left" class="celdas-titulo">
            <strong>Descripción:</strong><br><br>
            <?php echo $descripcion; ?>
        </td>
    </tr>
</table>    

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:100%; text-align:left" class="celdas-titulo">
            <strong>Observaciones:</strong><br><br>
            <?php echo $observaciones; ?>
        </td>
    </tr>
</table>    

<table style="table-layout:fixed; width:100%" >
    <tr>
        <td style="width:100%; text-align:left" class="celdas-titulo">
            <strong>Evaluador:</strong>
            &nbsp;
            <?php echo $evaluador; ?>
        </td>
    </tr>
</table>    
    
<style>

    @font-face {
    font-family: 'Open Sans';
    font-style: normal;
    font-weight: normal;
    src: url(http://themes.googleusercontent.com/static/fonts/opensans/v8/cJZKeOuBrn4kERxqtaUH3aCWcynf_cDxXwCLxiixG1c.ttf) format('truetype');
    }
    body{
        font-family: "source_sans_proregular", Calibri,Candara,Segoe,Segoe UI,Optima,Arial,sans-serif;            
    }

    .celdas-titulo{
        padding: 10px;
        border: 1px solid #000;        
    }

    table{
        border-collapse: collapse;
    }

</style>