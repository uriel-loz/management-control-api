<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>Recuperación de contraseña</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            background-color: #f5f7f9;
            color: #1f2937;
            -webkit-font-smoothing: antialiased;
        }

        .wrapper {
            width: 100%;
            background-color: #f5f7f9;
            padding: 40px 16px;
        }

        .container {
            max-width: 560px;
            margin: 0 auto;
            background-color: #ffffff;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 8px;
            overflow: hidden;
        }

        /* Header */
        .header {
            background-color: #0b0f19;
            padding: 28px 40px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .header-logo {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .header-logo .brand-main {
            font-size: 13px;
            font-weight: 700;
            color: #f9fafb;
            letter-spacing: 0.08em;
            text-transform: uppercase;
        }

        .header-logo .brand-sub {
            font-size: 10px;
            font-weight: 500;
            color: #06b6d4;
            letter-spacing: 0.12em;
            text-transform: uppercase;
        }

        /* Body */
        .body {
            padding: 40px;
        }

        .greeting {
            font-size: 22px;
            font-weight: 700;
            color: #0b0f19;
            margin-bottom: 16px;
            letter-spacing: -0.01em;
        }

        .paragraph {
            font-size: 15px;
            line-height: 1.65;
            color: #4b5563;
            margin-bottom: 12px;
        }

        /* CTA Button */
        .btn-wrapper {
            margin: 32px 0;
            text-align: center;
        }

        .btn {
            display: inline-block;
            background-color: #06b6d4;
            color: #0b0f19 !important;
            font-size: 15px;
            font-weight: 700;
            text-decoration: none;
            padding: 14px 36px;
            border-radius: 6px;
            letter-spacing: 0.01em;
        }

        /* Expiry notice */
        .notice-box {
            background-color: #f5f7f9;
            border: 1px solid rgba(0, 0, 0, 0.08);
            border-radius: 6px;
            padding: 14px 18px;
            margin-bottom: 24px;
        }

        .notice-box .notice-label {
            font-size: 11px;
            font-weight: 600;
            color: #6b7280;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-bottom: 4px;
        }

        .notice-box .notice-value {
            font-size: 14px;
            font-weight: 600;
            color: #0b0f19;
            font-family: 'Courier New', Courier, monospace;
        }

        .security-note {
            font-size: 13px;
            line-height: 1.6;
            color: #9ca3af;
            margin-bottom: 32px;
        }

        /* Fallback URL */
        .divider {
            border: none;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
        }

        .fallback-label {
            font-size: 12px;
            color: #6b7280;
            margin-bottom: 8px;
        }

        .fallback-url {
            font-size: 12px;
            color: #06b6d4;
            word-break: break-all;
            font-family: 'Courier New', Courier, monospace;
            line-height: 1.5;
        }

        /* Footer */
        .footer {
            background-color: #f5f7f9;
            border-top: 1px solid rgba(0, 0, 0, 0.06);
            padding: 20px 40px;
            text-align: center;
        }

        .footer p {
            font-size: 12px;
            color: #9ca3af;
            line-height: 1.5;
        }

        .footer .shield {
            display: inline-block;
            margin-bottom: 6px;
            font-size: 12px;
            color: #06b6d4;
            font-weight: 600;
            letter-spacing: 0.04em;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">

            <!-- Header -->
            <div class="header">
                <div class="header-logo">
                    <span class="brand-main">Management</span>
                    <span class="brand-sub">Control System</span>
                </div>
            </div>

            <!-- Body -->
            <div class="body">
                <p class="greeting">Hola, {{ $user_name }}</p>

                <p class="paragraph">
                    Recibimos una solicitud para restablecer la contraseña de tu cuenta.
                    Haz clic en el botón de abajo para crear una nueva contraseña.
                </p>

                <div class="btn-wrapper">
                    <a href="{{ $reset_url }}" class="btn" target="_blank" rel="noopener">
                        Restablecer contraseña
                    </a>
                </div>

                <div class="notice-box">
                    <div class="notice-label">Expiración del enlace</div>
                    <div class="notice-value">{{ $expires_in }} minutos</div>
                </div>

                <p class="security-note">
                    Si no solicitaste este cambio, puedes ignorar este correo con seguridad.
                    Tu contraseña permanecerá sin cambios y el enlace expirará automáticamente.
                </p>

                <hr class="divider" />

                <p class="fallback-label">
                    Si el botón no funciona, copia y pega este enlace en tu navegador:
                </p>
                <p class="fallback-url">{{ $reset_url }}</p>
            </div>

            <!-- Footer -->
            <div class="footer">
                <span class="shield">&#x1F512; Conexión segura SSL/TLS</span>
                <p>Management Control System &mdash; Este es un correo automático, no respondas a este mensaje.</p>
            </div>

        </div>
    </div>
</body>
</html>
