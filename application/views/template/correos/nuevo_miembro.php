<html lang="es" xml:lang="es" xmlns="http://www.w3.org/1999/xhtml" xmlns:cctd="http://www.constantcontact.com/cctd">
            <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
            <body topmargin="0" leftmargin="0" rightmargin="0">
                <div style="background: #fff; padding:20px 0px;">
                    <div align="center" style="margin:0 auto; width:100%; max-width: 600px; background: #fff; ">
                        <table width="100%" style=" background: #fff; " cellspacing="0" cellpadding="0" style="font-family:Arial, Helvetica, sans-serif;">
                            <tr height="100">
                                <td colspan="3" width="100%" align="center" style="background-color:#eee; color: #000; padding: 30px;">
                                    <p style="text-align: left; margin: 10px 0px;">
                                        ¡Hola <?php echo $nombre; ?>¡
                                    </p>
                                    <p style="text-align: justify;">
                                        El profesor <?php echo $nombre_profesor; ?> te ha invitado a que participes en el proyecto <?php echo $nombre_proyecto; ?>. link del proyecto: <a href="<?php echo base_url('proyectos/listar'); ?>"><?php echo base_url('proyectos/listar'); ?></a>
                                    </p>
                                </td>
                            </tr>
                            <tr height="20" style="background-color: #fbfbfb;">
                                <td colspan="3" width="600">&nbsp;</td>
                            </tr>
                            <tr height="0" style="background-color: #fbfbfb;">
                                <td colspan="3" width="100%" style="background-color:#fbfbfb; color: #000; padding: 10px 30px;">
                                    <p>
                                        Ten en cuenta los siguientes datos:
                                    </p>
                                </td>
                            </tr>
                            <tr height="25">
                                <td colspan="1" style="padding: 0 0 0 30px;">
                                    Fecha inicio:
                                </td>
                                <td colspan="2">
                                    <?php echo $fecha_inicio; ?>
                                </td>
                            </tr>
                            <tr height="25">
                                <td colspan="1" style="padding: 0 0 0 30px;">
                                    Fecha finalización:
                                </td>
                                <td colspan="2">
                                    <?php echo $fecha_fin; ?>
                                </td>
                            </tr>

                            <tr height="25">
                                <td colspan="1" style="padding: 0 0 0 30px;">
                                    Metodología:
                                </td>
                                <td colspan="2">
                                    <?php echo $metodologia; ?>
                                </td>
                            </tr>

                            <tr>
                                <td height="20"> &nbsp;
                                </td>
                            </tr>
                            <tr>
                                <td colspan="3">
                                    <table width="80%" align="center">
                                        <tbody align="center">
                                            <tr>
                                                <td height="20">
                                                    <h2 style="font-size: 28px; position: relative; margin-bottom: 1px; padding-bottom: 10px; border-bottom: 1px solid #ddd; color: #000"></h2>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td height="20"> &nbsp;
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <style type="text/css">
                    table {
                        color: #b3a2a4;
                        font-family: 'Roboto', sans-serif;
                    }
                </style>
            </body>
        </html>