#!/bin/sh
set -e

echo "🚀 Iniciando configuração do Laravel..."

# Aguardar o banco de dados ficar disponível
echo "⏳ Aguardando banco de dados..."
until pg_isready -h database -p 5432 -U vekant; do
  echo "Banco ainda não disponível, aguardando 2 segundos..."
  sleep 2
done
echo "✅ Banco de dados disponível!"

# Verificar se o .env existe, se não, copiar do .env.example
if [ ! -f /var/www/.env ]; then
    echo "📋 Criando arquivo .env..."
    cp /var/www/.env.example /var/www/.env
fi

# Gerar chave da aplicação se não existir
if ! grep -q "APP_KEY=base64:" /var/www/.env; then
    echo "🔑 Gerando chave da aplicação..."
    php artisan key:generate --no-interaction
fi

# Executar migrações
echo "🗃️  Executando migrações..."
php artisan migrate --force --no-interaction

# Criar link simbólico para storage
echo "🔗 Criando link do storage..."
php artisan storage:link --no-interaction || echo "Link já existe"

# Configurar permissões dos diretórios
echo "🔐 Configurando permissões..."
chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
chmod -R 775 /var/www/storage /var/www/bootstrap/cache

# Limpar e otimizar cache
echo "🧹 Otimizando cache..."
php artisan config:cache --no-interaction
php artisan route:cache --no-interaction
php artisan view:cache --no-interaction

# Criar usuário admin padrão se não existir
echo "👤 Verificando usuário admin..."
php artisan tinker --execute="
if (!App\Models\User::where('email', 'admin@topsol.com')->exists()) {
    App\Models\User::create([
        'name' => 'Administrador',
        'email' => 'admin@topsol.com',
        'password' => bcrypt('admin123'),
        'role' => 'admin'
    ]);
    echo 'Usuário admin criado: admin@topsol.com / admin123\n';
} else {
    echo 'Usuário admin já existe\n';
}
"

echo "✅ Configuração concluída! Iniciando PHP-FPM..."

# Executar o comando original
exec "$@"