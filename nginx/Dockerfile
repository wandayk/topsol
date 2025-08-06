FROM nginx:1.25-alpine

# Install OpenSSL for SSL certificates
RUN apk add --no-cache openssl

# Create SSL directory
RUN mkdir -p /etc/nginx/ssl

# Generate self-signed certificate for development
RUN openssl req -x509 -nodes -days 365 -newkey rsa:2048 \
    -keyout /etc/nginx/ssl/topsol.key \
    -out /etc/nginx/ssl/topsol.crt \
    -subj "/C=BR/ST=SP/L=Sao Paulo/O=TopSol/OU=IT/CN=localhost"

# Copy configuration files
COPY conf.d/ /etc/nginx/conf.d/
COPY nginx.conf /etc/nginx/nginx.conf

# Set proper permissions
RUN chown -R nginx:nginx /etc/nginx/ssl
RUN chmod 600 /etc/nginx/ssl/topsol.key
RUN chmod 644 /etc/nginx/ssl/topsol.crt

EXPOSE 80 443

CMD ["nginx", "-g", "daemon off;"]