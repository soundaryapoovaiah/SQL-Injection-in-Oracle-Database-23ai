#!/bin/bash
set -euo pipefail

ORACLE_USER="seed"
ORACLE_PASS="dees"
ORACLE_PDB="${ORACLE_PDB:-FREEPDB1}"

echo "[*] Waiting for Oracle to be ready..."
until echo "SELECT 1 FROM dual;" | sqlplus -s / as sysdba >/dev/null 2>&1; do
  sleep 2
done

echo "[*] Ensuring user ${ORACLE_USER} exists..."
sqlplus -s / as sysdba <<EOF
ALTER SESSION SET CONTAINER=${ORACLE_PDB};

DECLARE
  v_cnt NUMBER;
BEGIN
  SELECT COUNT(*) INTO v_cnt FROM dba_users WHERE username = UPPER('${ORACLE_USER}');
  IF v_cnt = 0 THEN
    EXECUTE IMMEDIATE 'CREATE USER ${ORACLE_USER} IDENTIFIED BY "${ORACLE_PASS}"';
    EXECUTE IMMEDIATE 'GRANT CREATE SESSION, CREATE TABLE, CREATE VIEW, CREATE PROCEDURE, CREATE SEQUENCE TO ${ORACLE_USER}';
    EXECUTE IMMEDIATE 'ALTER USER ${ORACLE_USER} QUOTA UNLIMITED ON USERS';
  END IF;
END;
/
EXIT;
EOF

echo "[*] Running lab schema SQL..."
sqlplus -s ${ORACLE_USER}/${ORACLE_PASS}@//localhost:1521/${ORACLE_PDB} @/opt/oracle/scripts/startup/02_sqllab_users_oracle.sql

echo "[*] Done."
