@echo off

start php artisan serve

start ngrok http --url=seagull-literate-strictly.ngrok-free.app 8000