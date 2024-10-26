FROM node:18-alpine

WORKDIR /app

# Add `/app/node_modules/.bin` to $PATH
ENV PATH /app/node_modules/.bin:$PATH

# Install app dependencies
COPY package.json ./
COPY package-lock.json ./
RUN npm install

# Add app
COPY . ./

# Start app
CMD ["npm", "start"]