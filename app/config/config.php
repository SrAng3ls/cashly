<?php
declare(strict_types=1);

// Configuración local de MySQL
const DB_HOST = '127.0.0.1';
const DB_NAME = 'cashly';
const DB_USER = 'root';
const DB_PASS = '';
const APP_NAME = 'Cashly';
const APP_URL = 'http://localhost:8000';
const SESSION_NAME = 'cashly_session';

if (session_status() === PHP_SESSION_NONE) {
    session_name(SESSION_NAME);
    session_start();
}

date_default_timezone_set('America/Bogota');
