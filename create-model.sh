#!/bin/bash

# Script untuk membuat multiple Laravel models sekaligus
# Usage: ./create-models.sh [options] ModelName1 ModelName2 ModelName3 ...
# Options:
#   -m  : Create migration
#   -f  : Create factory
#   -s  : Create seeder
#   -c  : Create controller
#   -r  : Create resource controller
#   -p  : Create policy
#   -a  : Create all (migration, factory, seeder, policy, controller)

# Warna untuk output
GREEN='\033[0;32m'
BLUE='\033[0;34m'
RED='\033[0;31m'
NC='\033[0m' # No Color

# Default options
OPTIONS=""
MODELS=()

# Parse arguments
while [[ $# -gt 0 ]]; do
    case $1 in
        -m|-f|-s|-c|-r|-p|-a|--migration|--factory|--seeder|--controller|--resource|--policy|--all)
            OPTIONS="$OPTIONS $1"
            shift
            ;;
        *)
            MODELS+=("$1")
            shift
            ;;
    esac
done

# Cek apakah ada model yang akan dibuat
if [ ${#MODELS[@]} -eq 0 ]; then
    echo -e "${RED}Error: Tidak ada model yang disebutkan!${NC}"
    echo ""
    echo "Usage: ./create-models.sh [options] ModelName1 ModelName2 ..."
    echo ""
    echo "Options:"
    echo "  -m, --migration    Create migration"
    echo "  -f, --factory      Create factory"
    echo "  -s, --seeder       Create seeder"
    echo "  -c, --controller   Create controller"
    echo "  -r, --resource     Create resource controller"
    echo "  -p, --policy       Create policy"
    echo "  -a, --all          Create all (migration, factory, seeder, policy, controller)"
    echo ""
    echo "Contoh:"
    echo "  ./create-models.sh Pesanan ItemPesanan"
    echo "  ./create-models.sh -m Pesanan ItemPesanan MetodePembayaran"
    echo "  ./create-models.sh -mfs User Product Category"
    echo "  ./create-models.sh -a Admin Customer"
    exit 1
fi

echo -e "${BLUE}======================================${NC}"
echo -e "${BLUE}  Laravel Model Creator${NC}"
echo -e "${BLUE}======================================${NC}"
echo ""
echo -e "Models yang akan dibuat: ${GREEN}${MODELS[@]}${NC}"
echo -e "Options: ${GREEN}${OPTIONS:-none}${NC}"
echo ""

# Konfirmasi
read -p "Lanjutkan? (y/n): " -n 1 -r
echo ""

if [[ ! $REPLY =~ ^[Yy]$ ]]; then
    echo -e "${RED}Dibatalkan.${NC}"
    exit 1
fi

echo ""
echo -e "${BLUE}Membuat models...${NC}"
echo ""

# Counter
SUCCESS=0
FAILED=0

# Loop untuk setiap model
for MODEL in "${MODELS[@]}"; do
    echo -e "${BLUE}→ Membuat model: ${GREEN}$MODEL${NC}"
    
    if php artisan make:model $MODEL $OPTIONS; then
        echo -e "${GREEN}✓ Berhasil membuat $MODEL${NC}"
        ((SUCCESS++))
    else
        echo -e "${RED}✗ Gagal membuat $MODEL${NC}"
        ((FAILED++))
    fi
    echo ""
done

# Summary
echo -e "${BLUE}======================================${NC}"
echo -e "${BLUE}  Summary${NC}"
echo -e "${BLUE}======================================${NC}"
echo -e "${GREEN}Berhasil: $SUCCESS${NC}"
echo -e "${RED}Gagal: $FAILED${NC}"
echo ""

if [ $SUCCESS -gt 0 ]; then
    echo -e "${GREEN}✓ Selesai!${NC}"
else
    echo -e "${RED}✗ Tidak ada model yang berhasil dibuat.${NC}"
fi