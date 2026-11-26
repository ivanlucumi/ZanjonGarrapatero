<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Acceso Bloqueado</title>
    <style>
        html, body {
            margin: 0;
            padding: 0;
            width: 100%;
            height: 100%;
            background-color: #000000 !important;
            color: #404040;
            font-family: system-ui, -apple-system, sans-serif;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            user-select: none;
            pointer-events: none;
        }
        .lock-box {
            text-align: center;
            padding: 24px;
        }
        .lock-box h1 {
            margin: 0 0 8px 0;
            font-size: 1.1rem;
            font-weight: 700;
            letter-spacing: 0.12em;
            text-transform: uppercase;
            color: #525252;
        }
        .lock-box p {
            margin: 0;
            font-size: 0.85rem;
            color: #333333;
        }
    </style>
</head>
<body oncontextmenu="return false;" onkeydown="return false;">
    <div class="lock-box">
        <h1>Dominio no autorizado</h1>
        <p>Esta plataforma está restringida al dominio registrado.</p>
    </div>
</body>
</html>
