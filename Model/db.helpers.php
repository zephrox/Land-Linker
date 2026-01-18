<?php

function db_es(mysqli $conn, string $s): string {
  return mysqli_real_escape_string($conn, $s);
}

function db_q(mysqli $conn, ?string $s): string {
  if ($s === null) return "NULL";
  return "'" . db_es($conn, $s) . "'";
}

function db_q_nullable(mysqli $conn, $v): string {
  if ($v === null || $v === '') return "NULL";
  return "'" . db_es($conn, (string)$v) . "'";
}

function db_int($v): string {
  if ($v === null || $v === '') return "NULL";
  return (string)((int)$v);
}

function db_float($v): string {
  if ($v === null || $v === '') return "NULL";
  return (string)((float)$v);
}

function db_bool01($v): string {
  return (string)((int)$v ? 1 : 0);
}
