$desktop = [Environment]::GetFolderPath('Desktop')
$wsh = New-Object -ComObject WScript.Shell

# 1. Acceso Directo Local
$s1 = $wsh.CreateShortcut("$desktop\Sistema Layra Fit.lnk")
$s1.TargetPath = "h:\MARELY\SISTEMA LAYRA FIT\iniciar_local.bat"
$s1.WorkingDirectory = "h:\MARELY\SISTEMA LAYRA FIT"
$s1.Description = "Iniciar Sistema Layra Fit (Local)"
$s1.IconLocation = "C:\Windows\System32\shell32.dll, 13" # Icono de red/mundo
$s1.Save()

# 2. Acceso Directo Móvil Público
$s2 = $wsh.CreateShortcut("$desktop\Sistema Layra Fit - URL Publica Mobile.lnk")
$s2.TargetPath = "h:\MARELY\SISTEMA LAYRA FIT\iniciar_publico_mobile.bat"
$s2.WorkingDirectory = "h:\MARELY\SISTEMA LAYRA FIT"
$s2.Description = "Generar URL Pública para Celular / Móvil"
$s2.IconLocation = "C:\Windows\System32\shell32.dll, 18" # Icono de teléfono/conexión
$s2.Save()

Write-Host "Accesos directos creados correctamente en el Escritorio:"
Write-Host "1. $desktop\Sistema Layra Fit.lnk"
Write-Host "2. $desktop\Sistema Layra Fit - URL Publica Mobile.lnk"
