# Iniciar túnel de Cloudflare persistente en segundo plano y obtener la URL
$logFile = "$env:TEMP\cloudflared_layra_public.log"

# Si ya hay un proceso cloudflared o log activo con URL, intentamos leerlo
$publicUrl = ""
if (Test-Path $logFile) {
    $content = Get-Content $logFile -Raw -ErrorAction SilentlyContinue
    if ($content -match "https://[-a-zA-Z0-9.]+\.trycloudflare\.com") {
        $publicUrl = $matches[0]
    }
}

if ([string]::IsNullOrEmpty($publicUrl)) {
    # Eliminar log viejo si no tenía URL válida
    if (Test-Path $logFile) { Remove-Item $logFile -Force }
    
    $cloudflaredExe = "C:\Users\Miguel\AppData\Local\npm-cache\_npx\8a26fc3a61fe4212\node_modules\cloudflared\bin\cloudflared.exe"
    if (Test-Path $cloudflaredExe) {
        $proc = Start-Process -FilePath $cloudflaredExe -ArgumentList "tunnel --url http://127.0.0.1:8000" -RedirectStandardError $logFile -WorkingDirectory "h:\MARELY\SISTEMA LAYRA FIT" -WindowStyle Minimized -PassThru
    } else {
        $proc = Start-Process -FilePath "cmd.exe" -ArgumentList "/c npx -y cloudflared tunnel --url http://127.0.0.1:8000 > `"$logFile`" 2>&1" -WorkingDirectory "h:\MARELY\SISTEMA LAYRA FIT" -WindowStyle Minimized -PassThru
    }

    $attempts = 0
    while ([string]::IsNullOrEmpty($publicUrl) -and $attempts -lt 20) {
        Start-Sleep -Seconds 1
        $attempts++
        if (Test-Path $logFile) {
            $content = Get-Content $logFile -Raw -ErrorAction SilentlyContinue
            if ($content -match "https://[-a-zA-Z0-9.]+\.trycloudflare\.com") {
                $publicUrl = $matches[0]
            }
        }
    }
}

Write-Host "PUBLIC_URL:$publicUrl"
