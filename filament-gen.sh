#!/bin/bash

RESOURCE_NAME=""
USE_SINGULAR=false
VIEW="--view"
GENERATE="--generate"
OPTIONS=""

# Get resource name first
for arg in "$@"; do
    if [[ ! $arg == --* ]] && [[ -z "$RESOURCE_NAME" ]]; then
        RESOURCE_NAME=$arg
        break
    fi
done

# Parse flags
while [[ $# -gt 0 ]]; do
    case $1 in
        --no-view)
            VIEW=""
            shift
            ;;
        --no-generate)
            GENERATE=""
            shift
            ;;
        --simple)
            OPTIONS="$OPTIONS --simple"
            shift
            ;;
        --singular)
            USE_SINGULAR=true
            shift
            ;;
        *)
            shift
            ;;
    esac
done

if [ -z "$RESOURCE_NAME" ]; then
    echo "❌ Error: Resource name required"
    echo "Usage: ./filament-gen.sh ResourceName [--singular] [--no-view] [--no-generate] [--simple]"
    exit 1
fi

echo "🚀 Generating Filament Resource: $RESOURCE_NAME"

# Generate resource
php artisan make:filament-resource $RESOURCE_NAME $VIEW $GENERATE $OPTIONS

if [ $? -ne 0 ]; then
    echo "❌ Failed to create resource"
    exit 1
fi

# Apply singular modifications if requested
if [ "$USE_SINGULAR" = true ]; then
    echo "📝 Applying singular naming convention..."
    
    # Try both possible locations
    FILE1="app/Filament/Resources/${RESOURCE_NAME}Resource.php"
    FILE2="app/Filament/Resources/${RESOURCE_NAME}s/${RESOURCE_NAME}Resource.php"
    
    if [ -f "$FILE1" ]; then
        FILE="$FILE1"
    elif [ -f "$FILE2" ]; then
        FILE="$FILE2"
        # Rename folder from plural to singular
        PLURAL_DIR="app/Filament/Resources/${RESOURCE_NAME}s"
        SINGULAR_DIR="app/Filament/Resources/${RESOURCE_NAME}"
        
        if [ -d "$PLURAL_DIR" ]; then
            mv "$PLURAL_DIR" "$SINGULAR_DIR"
            FILE="$SINGULAR_DIR/${RESOURCE_NAME}Resource.php"
            echo "📁 Renamed folder to singular: ${RESOURCE_NAME}"
        fi
    else
        echo "❌ Resource file not found in expected locations"
        exit 1
    fi
    
    # Create slug (convert PascalCase to kebab-case)
    SLUG=$(echo "$RESOURCE_NAME" | sed 's/\([A-Z]\)/-\1/g' | sed 's/^-//' | tr '[:upper:]' '[:lower:]')
    
    # Escape special characters for sed
    RESOURCE_ESCAPED=$(echo "$RESOURCE_NAME" | sed 's/[]\/$*.^[]/\\&/g')
    
    # Insert properties after $model line using a more compatible sed syntax
    awk -v name="$RESOURCE_NAME" -v slug="$SLUG" '
    /protected static \?string \$model/ {
        print
        print ""
        print "    protected static ?string $modelLabel = '\''" name "'\'';"
        print "    protected static ?string $pluralModelLabel = '\''" name "'\'';"
        print "    protected static ?string $navigationLabel = '\''" name "'\'';"
        print "    protected static ?string $slug = '\''" slug "'\'';"
        next
    }
    { print }
    ' "$FILE" > "$FILE.tmp" && mv "$FILE.tmp" "$FILE"
    
    # Update namespace if folder was renamed
    if [ -d "app/Filament/Resources/${RESOURCE_NAME}" ]; then
        # Update all PHP files in the resource folder
        find "app/Filament/Resources/${RESOURCE_NAME}" -name "*.php" -type f -exec sed -i.bak "s/Resources\\\\${RESOURCE_NAME}s/Resources\\\\${RESOURCE_NAME}/g" {} \;
        find "app/Filament/Resources/${RESOURCE_NAME}" -name "*.bak" -type f -delete
    fi
    
    echo "✅ Singular properties applied!"
fi

echo "✅ Success! Resource created at:"
if [ -f "app/Filament/Resources/${RESOURCE_NAME}Resource.php" ]; then
    echo "   app/Filament/Resources/${RESOURCE_NAME}Resource.php"
elif [ -f "app/Filament/Resources/${RESOURCE_NAME}/${RESOURCE_NAME}Resource.php" ]; then
    echo "   app/Filament/Resources/${RESOURCE_NAME}/${RESOURCE_NAME}Resource.php"
fi
