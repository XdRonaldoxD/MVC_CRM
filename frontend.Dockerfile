# Etapa 1: clonar el frontend compilado desde GitHub
FROM alpine/git AS clone
WORKDIR /app
RUN git clone https://github.com/XdRonaldoxD/Administrador_mvc.git .

# Etapa 2: servir con Nginx
FROM nginx:alpine
COPY --from=clone /app/dist/AdminCarritoCompras /usr/share/nginx/html
COPY nginx-frontend.conf /etc/nginx/conf.d/default.conf
EXPOSE 80
