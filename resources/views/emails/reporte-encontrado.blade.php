<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
</head>
<body style="font-family: sans-serif; color: #1C2B24;">
    <h2>¡Hola {{ $reporte->nombre_dueno }, tenemos buenas noticias!</h2>
    <p>Tu reporte fue marcado como <strong>Encontrado</strong> en RastroPet.</p>

    <table style="border-collapse: collapse; margin-top: 16px;">
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Número de reporte:</td>
            <td style="padding: 6px 12px;">{{ $reporte->numero_reporte }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Mascota:</td>
            <td style="padding: 6px 12px;">{{ $reporte->mascota }} ({{ $reporte->especie }})</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Estado:</td>
            <td style="padding: 6px 12px;">{{ $reporte->estado }}</td>
        </tr>
        <tr>
            <td style="padding: 6px 12px; font-weight: bold;">Zona:</td>
            <td style="padding: 6px 12px;">{{ $reporte->zona->nombre }}</td>
        </tr>
    </table>

    <p style="margin-top: 20px;">¡Gracias por confiar en RastroPet!</p>
</body>
</html>
