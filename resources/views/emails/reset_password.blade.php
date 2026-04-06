<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Restablecer Contraseña</title>
</head>
<body style="margin: 0 !important; padding: 0 !important; width: 100% !important; background-color: #f5f6f8; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-text-size-adjust: 100%; -ms-text-size-adjust: 100%;">
<center style="width: 100%; background-color: #f5f6f8;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="table-layout: fixed; background-color: #f5f6f8; mso-table-lspace: 0pt; mso-table-rspace: 0pt;">
        <tr>
            <td align="center" style="padding: 40px 10px;">
                <!-- Contenedor Principal (Card) -->
                <table border="0" cellpadding="0" cellspacing="0" width="550" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border-collapse: separate !important; width: 100%; max-width: 550px;">

                    <!-- Header Adminto Dark -->
                    <tr>
                        <td align="center" bgcolor="#313a46" style="padding: 35px; background-color: #313a46;">
                            <img src="{{ asset('images/logo_oficial.png') }}" alt="Logo" height="40" style="display: block; border: 0; outline: none; text-decoration: none; color: #ffffff; font-family: sans-serif; font-size: 20px; font-weight: bold; height: 40px;">
                        </td>
                    </tr>

                    <!-- Contenido -->
                    <tr>
                        <td style="padding: 40px; text-align: center; background-color: #ffffff;">

                            <h2 style="color: #313a46; font-size: 24px; margin: 0 0 10px 0; font-weight: 600; font-family: 'Segoe UI', Arial, sans-serif;">¡Hola, {{ $userName }}!</h2>

                            <p style="color: #71b6f9; font-size: 13px; margin: 0 0 20px 0; font-weight: 700; text-transform: uppercase; letter-spacing: 1.5px; font-family: sans-serif;">Solicitud de seguridad</p>

                            <p style="color: #98a6ad; font-size: 15px; line-height: 1.7; margin: 0 0 25px 0; font-family: 'Segoe UI', Arial, sans-serif;">
                                Has solicitado restablecer tu contraseña para la cuenta asociada a <br>
                                <strong style="color: #313a46;">{{ $email }}</strong>. En <strong>{{ config('app.name') }}</strong> nos tomamos muy en serio la seguridad de tu cuenta.
                            </p>

                            <div style="margin: 30px 0; border-top: 1px solid #f1f3fa; line-height: 1px; font-size: 1px;">&nbsp;</div>

                            <p style="color: #98a6ad; font-size: 14px; margin: 0 0 15px 0; font-family: 'Segoe UI', Arial, sans-serif;">
                                Utiliza el siguiente código de seguridad para continuar con el proceso:
                            </p>

                            <!-- Token Box -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f1f3fa; border-radius: 6px; border: 1px solid #e3eaef; margin-bottom: 25px;">
                                <tr>
                                    <td align="center" style="padding: 20px; font-family: 'Courier New', Courier, monospace; font-size: 26px; font-weight: bold; color: #313a46; letter-spacing: 6px;">
                                        {{ $token }}
                                    </td>
                                </tr>
                            </table>

                            <!-- Nota de aviso -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 15px !important;">
                                <tr>
                                    <td style="padding: 15px; background-color: #fef5f6; border-left: 4px solid #f1556c; border-radius: 4px; text-align: left;">
                                        <p style="margin: 0; color: #f1556c; font-size: 13px; font-family: 'Segoe UI', Arial, sans-serif; line-height: 1.4;">
                                            <strong style="text-transform: uppercase; font-size: 11px;">Importante:</strong> Este código expirará automáticamente en <strong>20 minutos</strong> por motivos de seguridad.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td bgcolor="#ffffff" style="padding: 30px; text-align: center; border-top: 1px solid #f1f3fa; background-color: #ffffff;">
                            <p style="margin: 0; color: #98a6ad; font-size: 13px; font-family: 'Segoe UI', Arial, sans-serif;">
                                &copy; {{ date('Y') }} <strong>{{ config('app.name') }}</strong>. Todos los derechos reservados.
                            </p>
                            <p style="margin: 8px 0 0 0; color: #adb5bd; font-size: 12px; font-family: 'Segoe UI', Arial, sans-serif;">
                                Si no solicitaste este cambio, simplemente ignora este correo.
                            </p>

                            <p style="margin: 20px 0 0 0; color: #adb5bd; font-size: 15px; text-align: center; font-family: sans-serif;">
                                Este es un correo automático, por favor no lo respondas.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</center>
</body>
</html>
