# Uploaer API Dockerfile

# We will be using httpd base image since our client application are pure javascript using Angular Framework
FROM httpd

# Update default httpd config
RUN sed -ie '$ a Include /usr/local/apache2/conf/000-upload-client.conf' /usr/local/apache2/conf/httpd.conf

# Enable Rewrite Module
RUN sed -ie 's/#LoadModule rewrite_module/LoadModule rewrite_module/g' /usr/local/apache2/conf/httpd.conf 

# Copy Apache vhost configuration
COPY ./build_config/httpd/000-upload-client.conf /usr/local/apache2/conf/000-upload-client.conf

# Copy in source files, which we need if we are doing a
# bundled (non-local) build
COPY app/. /usr/local/apache2/htdocs/app

WORKDIR /usr/local/apache2/htdocs/app

ENTRYPOINT ["bash", "-c"]
CMD ["httpd-foreground"]