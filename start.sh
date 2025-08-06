#!/bin/bash

echo "🚀 TopSol - Sistema de Gestão"
echo "=============================="

# Verificar se Docker está rodando
if ! docker info > /dev/null 2>&1; then
    echo "❌ Docker não está rodando. Por favor, inicie o Docker primeiro."
    exit 1
fi

# Verificar se Docker Compose está disponível
if ! command -v docker-compose > /dev/null 2>&1; then
    echo "❌ Docker Compose não encontrado. Por favor, instale o Docker Compose."
    exit 1
fi

echo "📦 Parando containers existentes..."
docker-compose down 2>/dev/null || true

echo "🔨 Construindo e iniciando containers..."
docker-compose up --build -d

echo "⏳ Aguardando inicialização dos serviços..."
sleep 10

# Verificar status dos containers
echo "📊 Status dos containers:"
docker-compose ps

echo ""
echo "✅ Sistema TopSol iniciado com sucesso!"
echo ""
echo "🌐 Acessos disponíveis:"
echo "   • Frontend: http://localhost:4200"
echo "   • Database: localhost:5432 (topsol/topsol123)"
echo ""
echo "👤 Usuário padrão:"
echo "   • Email: admin@topsol.com"
echo "   • Senha: admin123"
echo ""
echo "📋 Comandos úteis:"
echo "   • Ver logs: docker-compose logs -f [serviço]"
echo "   • Parar: docker-compose down"
echo "   • Status: docker-compose ps"
echo ""