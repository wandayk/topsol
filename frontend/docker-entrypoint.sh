#!/bin/sh
set -e

echo "🌐 Iniciando configuração do Frontend Angular..."

# Verificar se node_modules existe, caso contrário, instalar dependências
if [ ! -d "node_modules" ]; then
    echo "📦 Instalando dependências do Node.js..."
    npm install
fi

# Forçar build para compilar o Tailwind CSS
echo "🎨 Compilando Tailwind CSS..."
npm run build

echo "🚀 Iniciando servidor de desenvolvimento Angular..."

# Executar o comando original
exec "$@"