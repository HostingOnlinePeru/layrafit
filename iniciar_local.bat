@echo off
chcp 65001 >nul
powershell -Command "$c = Get-NetTCPConnection -LocalPort 8000 -ErrorAction SilentlyContinue; if (-not $c) { Start-Process php -ArgumentList 'artisan serve --host=0.0.0.0 --port=8000' -WorkingDirectory 'h:\MARELY\SISTEMA LAYRA FIT' -WindowStyle Hidden }"
start http://localhost:8000
