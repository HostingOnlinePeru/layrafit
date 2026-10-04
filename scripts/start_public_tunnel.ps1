# Script para iniciar Tunnel Público y generar URL Móvil
$ErrorActionPreference = "Stop"

Write-Host "===================================================" -ForegroundColor Cyan
Write-Host "     SISTEMA LAYRA FIT - URL PÚBLICA PARA MÓVIL    " -ForegroundColor Yellow
Write-Host "===================================================" -ForegroundColor Cyan
Write-Host ""

# 1. Verificar si el servidor local está activo en puerto 8000
$portCheck = Test-NetConnection -ComputerName 127.0.0.1 -Port 8000 -WarningAction SilentlyContinue
if (-not $portCheck.TcpTestSucceeded) {
    Write-Host "[1/3] Iniciando servidor local Laravel en puerto 8000..." -ForegroundColor Green
    Start-Process -FilePath "php" -ArgumentList "artisan serve --host=0.0.0.0 --port=8000" -WorkingDirectory "h:\MARELY\SISTEMA LAYRA FIT" -WindowStyle Minimized
    Start-Sleep -Seconds 3
} else {
    Write-Host "[1/3] Servidor local Laravel detectado activo en puerto 8000." -ForegroundColor Green
}

# 2. Iniciar Cloudflare Tunnel
Write-Host "[2/3] Generando URL pública segura (HTTPS)..." -ForegroundColor Green
$logFile = "$env:TEMP\cloudflared_layra.log"
if (Test-Path $logFile) { Remove-Item $logFile -Force }

$cloudflaredExe = "C:\Users\Miguel\AppData\Local\npm-cache\_npx\8a26fc3a61fe4212\node_modules\cloudflared\bin\cloudflared.exe"

if (Test-Path $cloudflaredExe) {
    $proc = Start-Process -FilePath $cloudflaredExe -ArgumentList "tunnel --url http://127.0.0.1:8000" -RedirectStandardError $logFile -WorkingDirectory "h:\MARELY\SISTEMA LAYRA FIT" -WindowStyle Minimized -PassThru
} else {
    $proc = Start-Process -FilePath "cmd.exe" -ArgumentList "/c npx -y cloudflared tunnel --url http://127.0.0.1:8000 > `"$logFile`" 2>&1" -WorkingDirectory "h:\MARELY\SISTEMA LAYRA FIT" -WindowStyle Minimized -PassThru
}

# 3. Extraer la URL Pública del log
$publicUrl = ""
$attempts = 0
while ([string]::IsNullOrEmpty($publicUrl) -and $attempts -lt 25) {
    Start-Sleep -Seconds 1
    $attempts++
    if (Test-Path $logFile) {
        $content = Get-Content $logFile -Raw -ErrorAction SilentlyContinue
        if ($content -match "https://[-a-zA-Z0-9.]+\.trycloudflare\.com") {
            $publicUrl = $matches[0]
        }
    }
}

if ([string]::IsNullOrEmpty($publicUrl)) {
    Write-Host "No se pudo obtener la URL automáticamente. Revisa $logFile" -ForegroundColor Red
    pause
    exit 1
}

# 4. Copiar al Portapapeles y mostrar en pantalla
Set-Clipboard -Value $publicUrl

Write-Host ""
Write-Host "===================================================" -ForegroundColor Green
Write-Host " ¡URL PÚBLICA GENERADA CON ÉXITO!" -ForegroundColor Yellow
Write-Host "===================================================" -ForegroundColor Green
Write-Host ""
Write-Host " URL Móvil:  " -NoNewline
Write-Host "$publicUrl" -ForegroundColor Cyan -BackgroundColor DarkBlue
Write-Host ""
Write-Host " (La URL ha sido copiada automáticamente a tu portapapeles)" -ForegroundColor Gray
Write-Host ""

# Generar interfaz HTML interactiva con Código QR para la cámara del celular
$qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=350x350&data=$publicUrl"
$htmlContent = @"
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Acceso Móvil - Sistema Layra Fit</title>
    <style>
        body { font-family: 'Segoe UI', Roboto, sans-serif; background: #0f172a; color: #f8fafc; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: #1e293b; border-radius: 20px; padding: 30px; text-align: center; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.5); max-width: 440px; width: 90%; border: 1px solid #334155; }
        h1 { color: #38bdf8; margin-top: 0; font-size: 24px; }
        p { color: #94a3b8; font-size: 14px; line-height: 1.5; }
        .qr-box { background: white; padding: 15px; border-radius: 12px; display: inline-block; margin: 20px 0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.3); }
        .qr-box img { display: block; width: 250px; height: 250px; }
        .url-box { background: #0f172a; padding: 14px; border-radius: 8px; word-break: break-all; color: #38bdf8; font-weight: bold; font-size: 16px; margin-bottom: 20px; border: 1px dashed #38bdf8; user-select: all; }
        .btn { background: #0284c7; color: white; border: none; padding: 12px 24px; border-radius: 8px; font-weight: bold; cursor: pointer; text-decoration: none; display: inline-block; transition: background 0.2s; }
        .btn:hover { background: #0369a1; }
    </style>
</head>
<body>
    <div class="card">
        <h1>📱 Acceso Móvil Layra Fit</h1>
        <p>Apunta la cámara de tu celular o tablet al código QR para ingresar de inmediato al sistema desde cualquier lugar:</p>
        
        <div class="qr-box">
            <img src="$qrUrl" alt="Código QR Sistema Layra Fit">
        </div>
        
        <div class="url-box">$publicUrl</div>
        
        <a href="$publicUrl" target="_blank" class="btn">Abrir en el navegador</a>
    </div>
</body>
</html>
"@

$htmlPath = "$env:TEMP\Acceso_Mobile_Layra_Fit.html"
Set-Content -Path $htmlPath -Value $htmlContent -Encoding UTF8
Start-Process $htmlPath

Write-Host "Escanea el código QR que se ha abierto en tu pantalla con la cámara de tu celular." -ForegroundColor Yellow
Write-Host "Mantén esta ventana abierta mientras utilices la app en tu celular." -ForegroundColor Gray
Write-Host ""
Write-Host "Presiona cualquier tecla cuando desees cerrar la URL pública y el túnel..." -ForegroundColor DarkGray
$null = $Host.UI.RawUI.ReadKey("NoEcho,IncludeKeyDown")

if ($proc -and -not $proc.HasExited) {
    Stop-Process -Id $proc.Id -Force -ErrorAction SilentlyContinue
}
