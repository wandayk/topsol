# Guia de Configuração - TopSol v2.0

## Pré-requisitos

- Docker e Docker Compose instalados
- Node.js 20+ (para desenvolvimento local)
- Git

## Instalação e Configuração

### Método 1: Início Rápido (Recomendado)

```bash
# Executar script de início automático
./start.sh
```

Este script irá:
- Verificar dependências (Docker, Docker Compose)
- Parar containers existentes
- Construir e iniciar todos os serviços
- Configurar automaticamente o backend (chave, migrações, usuário admin)
- Exibir informações de acesso

### Método 2: Manual

```bash
# Construir e iniciar todos os serviços
docker-compose up --build -d

# Verificar status dos containers
docker-compose ps
```

**Nota**: Com o novo sistema, tudo é configurado automaticamente:
- ✅ Dependências instaladas durante o build
- ✅ Chave da aplicação gerada automaticamente
- ✅ Migrações executadas na inicialização
- ✅ Usuário admin criado (admin@topsol.com / admin123)
- ✅ Cache e otimizações aplicadas

## Acessos

- **Frontend**: https://localhost (produção) ou http://localhost:4200 (desenvolvimento)
- **Backend API**: https://localhost/api
- **Database**: localhost:5432 (topsol/your_password)
- **Redis**: localhost:6379

## Usuário Padrão

Após executar os seeders:
- **Email**: admin@topsol.com
- **Senha**: admin123

## Comandos Úteis

### Laravel
```bash
# Ver logs
docker-compose logs backend

# Executar tinker
docker-compose exec backend php artisan tinker

# Limpar cache
docker-compose exec backend php artisan cache:clear

# Executar testes
docker-compose exec backend php artisan test
```

### Angular
```bash
# Ver logs do frontend
docker-compose logs frontend

# Executar testes
docker-compose exec frontend npm test

# Build de produção
docker-compose exec frontend npm run build
```

### Database
```bash
# Backup do banco
docker-compose exec database pg_dump -U topsol topsol > backup.sql

# Restaurar backup
docker-compose exec -T database psql -U topsol topsol < backup.sql
```

## Desenvolvimento

### Estrutura de Arquivos
```
top-sol/
├── backend/              # Laravel API
│   ├── app/
│   ├── config/
│   ├── database/
│   └── routes/
├── frontend/             # Angular SPA
│   ├── src/
│   └── dist/
├── nginx/                # Configurações NGINX
├── database/             # Scripts de DB
└── legacy/               # Sistema antigo
```

### Hot Reload

- Backend: Alterações em PHP são refletidas automaticamente
- Frontend: Angular CLI com hot reload ativo na porta 4200

### Logs

```bash
# Ver logs de todos os serviços
docker-compose logs -f

# Ver logs específicos
docker-compose logs -f backend
docker-compose logs -f frontend
docker-compose logs -f nginx
```

## Solução de Problemas

### Container não inicia
```bash
# Verificar logs
docker-compose logs [service_name]

# Reconstruir container
docker-compose build --no-cache [service_name]
```

### Erro de permissão
```bash
# Ajustar permissões (Laravel)
docker-compose exec backend chown -R www:www /var/www/storage
docker-compose exec backend chmod -R 775 /var/www/storage
```

### Banco não conecta
```bash
# Verificar se PostgreSQL está rodando
docker-compose ps database

# Reiniciar database
docker-compose restart database
```

## Migração do Sistema Legacy

O sistema antigo está preservado na pasta `legacy/` para referência. Os dados podem ser migrados usando os scripts em `database/migration/` (quando implementados).

## Segurança

- HTTPS obrigatório em produção
- Tokens JWT para autenticação
- Rate limiting configurado
- Headers de segurança implementados
- Validação rigorosa de uploads