#!/usr/bin/env bash
set -euo pipefail

ORACLE_CONT="oracle-10.9.0.7"
PDB="FREEPDB1"
USER="seed"
PASS="dees"
SQL="/opt/oracle/scripts/startup/02_sqllab_users_oracle.sql"

echo "[lab-init] Waiting for Oracle to accept connections..."
until docker exec "$ORACLE_CONT" bash -lc "echo 'SELECT 1 FROM dual;' | sqlplus -s ${USER}/${PASS}@//localhost:1521/${PDB} >/dev/null 2>&1"; do
  sleep 2
done

echo "[lab-init] Running lab SQL..."
docker exec "$ORACLE_CONT" bash -lc "sqlplus -s ${USER}/${PASS}@//localhost:1521/${PDB} @${SQL}"

echo "[lab-init] Verifying tables..."
docker exec "$ORACLE_CONT" bash -lc "echo \"SELECT table_name FROM user_tables ORDER BY table_name;\" | sqlplus -s ${USER}/${PASS}@//localhost:1521/${PDB}"

echo "[lab-init] Done."
