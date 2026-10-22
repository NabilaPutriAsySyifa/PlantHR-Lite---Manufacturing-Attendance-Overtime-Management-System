#!/usr/bin/env bash
set -eo pipefail

DB_NAME="planthr_db"
DB_USER="root"
DB_PASS=""
DB_HOST="127.0.0.1"
BACKUP_DIR="/var/backups/planthr"
DATE_STAMP=$(date +'%Y%m%d_%H%M%S')
TARGET_FILE="${BACKUP_DIR}/${DB_NAME}_${DATE_STAMP}.sql.gz"
RETENTION_DAYS=7

mkdir -p "${BACKUP_DIR}"

if [ -z "${DB_PASS}" ]; then
  mysqldump -h "${DB_HOST}" -u "${DB_USER}" --single-transaction --routines "${DB_NAME}" | gzip > "${TARGET_FILE}"
else
  mysqldump -h "${DB_HOST}" -u "${DB_USER}" -p"${DB_PASS}" --single-transaction --routines "${DB_NAME}" | gzip > "${TARGET_FILE}"
fi

find "${BACKUP_DIR}" -type f -name "${DB_NAME}_*.sql.gz" -mtime +${RETENTION_DAYS} -delete
