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

# Copia todo o resto do projeto
COPY . .

# Declara que /app/database é um volume (persistência)
VOLUME ["/app/database"]

# Usa o usuário não-root
USER appuser

EXPOSE 3000

CMD ["npm", "start"]
