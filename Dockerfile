FROM node:18-alpine

# Cria um usuário não-root para maior segurança
RUN addgroup -S appgroup && adduser -S appuser -G appgroup

WORKDIR /app

# Copia arquivos de dependências e instala
COPY package*.json ./
RUN npm install

# Cria a pasta de banco de dados e ajusta permissões
RUN mkdir -p /app/database
RUN chown -R appuser:appgroup /app

# Copia o restante do projeto
COPY . .

# Declara o volume para persistência (configure no Dokploy: ../files/app/AppName → /app/database)
VOLUME ["/app/database"]

# Usa o usuário não-root
USER appuser

EXPOSE 3000

CMD ["npm", "start"]
