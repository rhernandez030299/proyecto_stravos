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
                                        El estudiante <?php echo $nombre_usuario ?> ha finalizado el modulo <?php echo $nombre_modulo ?>, si deseas saber mas sobre este modulo da clic en el enlace: <a href="<?php echo $url ?>"><?php echo $url ?></a>
                                    </p>
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