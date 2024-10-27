FROM node:18-alpine

WORKDIR /app

# Add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH
ENV NODE_ENV=development
ENV NODE_OPTIONS=--openssl-legacy-provider

# Install app dependencies
COPY package*.json ./

# Instalar react-scripts globalmente
RUN npm install -g react-scripts@5.0.1

# Instalar dependencias del proyecto
RUN npm install

# Add app
COPY . ./

# Start app
CMD ["npm", "start"]