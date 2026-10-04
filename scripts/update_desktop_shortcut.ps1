$desktop = [Environment]::GetFolderPath('Desktop')

# Eliminar el acceso directo redundante
$mobileLink = Join-Path $desktop "Sistema Layra Fit - URL Publica Mobile.lnk"
if (Test-Path $mobileLink) {
    Remove-Item $mobileLink -Force
}

# Crear o actualizar acceso directo local único
$wsh = New-Object -ComObject WScript.Shell
$shortcutPath = Join-Path $desktop "Sistema Layra Fit.lnk"
$shortcut = $wsh.CreateShortcut($shortcutPath)
$shortcut.TargetPath = "h:\MARELY\SISTEMA LAYRA FIT\iniciar_local.bat"
$shortcut.WorkingDirectory = "h:\MARELY\SISTEMA LAYRA FIT"
$shortcut.Description = "Sistema Layra Fit Local"
$shortcut.IconLocation = "C:\Windows\System32\shell32.dll, 13"
$shortcut.Save()

Write-Host "Acceso directo único configurado exitosamente en: $shortcutPath"
