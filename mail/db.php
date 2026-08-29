<?php
/**
 * Lightweight database connector shared by the mail endpoints, used to
 * persist leads/contact messages for the separate admin panel (admin/) to
 * read. Deliberately plain mysqli — the public site has no dependency on
 * the admin panel's CodeIgniter framework, only on this same database.
 *
 * Storage is treated as best-effort: if the database is unreachable, the
 * calling endpoint should catch the exception and continue with email
 * sending regardless, so a lead is never silently lost on both fronts
 * unless email also fails (which is already surfaced to the user).
 */

function finzo_db_connect(): ?mysqli
{
    static $conn = null;
    static $attempted = false;

    if ($attempted) {
        return $conn;
    }
    $attempted = true;

    try {
        $mysqli = @new mysqli('localhost', 'root', '', 'finzo_admin', 3306);
        if ($mysqli->connect_errno) {
            error_log('[Finzo DB] Connection failed: ' . $mysqli->connect_error);
            return null;
        }
        $mysqli->set_charset('utf8mb4');
        $conn = $mysqli;
        return $conn;
    } catch (\Throwable $e) {
        error_log('[Finzo DB] Connection exception: ' . $e->getMessage());
        return null;
    }
}
