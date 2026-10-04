@echo off
title Sistema Layra Fit - Acceso Móvil
chcp 65001 >nul
powershell -ExecutionPolicy Bypass -File "%~dp0scripts\start_public_tunnel.ps1"
