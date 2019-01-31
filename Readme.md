# Dockerize Angular and ZF2 Rest API for Google Drive

Simple file uploader to Google Drive build on top of Docker using Angular as client application and Zend Framework 2 as REST API.

## Getting Started

These instructions will get you a copy of the project up and running on your local machine for development and testing purposes. 

### Prerequisites

What things you need to install the software and how to install them

- [PHP >= 7.1](http://www.wampserver.com/en/) 
- [Composer](https://getcomposer.org/download/)- We will be using this to install composer dependencies for our server application.
- [Docker](https://www.docker.com/) - We will be running our application using docker containers.
- [Node Package Manager](https://nodejs.org/en/) - We will be using this to install node modules for our client application.
- [Git](https://git-scm.com/) :blush:
- [AngularCLI](https://cli.angular.io/) - for running the client locally

### Google Drive Application Key and Secret

You can follow the [Quickstart Step 1](https://developers.google.com/drive/api/v3/quickstart/php) to generate our ```credentials.json``` file or setup your own key and secret [here](https://console.developers.google.com/apis/credentials) and follow [this](https://www.iperiusbackup.net/en/how-to-enable-google-drive-api-and-get-client-credentials/) tutorial on how to generate.

Note: Keep your downloaded ```credentials.json``` and we will be using that later.

### Installing

Clone the repository:
```
git clone https://github.com/kevspadilla/docker-angular-zf2-gdrive.git [desired_project_name]
```
Lets go inside the ```desired_project_name/source/api/app``` and install our composer dependencies. 
```
cd desired_project_name/source/api/app
composer self-update
composer install
```

Next, lets install our client dependencies, inside ```desired_project_name/source/client/app```
```
cd desired_project_name/source/client/app
npm install
```

To make sure that our client application is working properly run the command below. This will build our client application and serve it on our localhost.
```
ng build
ng serve
```
And go to http://localhost:4200/. 

:+1: Great! Now our client is working properly, now lets set-up out server application.

Going back to our server codebase, copy the content of our downloaded ```credentials.json``` to ```desired_project_name/source/api/app/config/autoload/credentials.json```

And generate our Google Drive Access Token to give our application upload access to your Google Drive.
```
composer run-script generate-google-token
```
If you are having trouble with ```Guzzle``` and SSL while executing the command. Check out [this post](https://stackoverflow.com/questions/24611640/curl-60-ssl-certificate-unable-to-get-local-issuer-certificate) on how to fix it.

Once you are done with the step, you should see a message like this: 
```
Google Auth Token Generated
```

Now lets see if our api is working, run the command below and access http://localhost:8080/, and you should see an awesome message.
```
php -S 0.0.0.0:8080 -t public/ public/index.php
```

## The Fun Begins

Let's run our application using docker container and ``docker-compose``

Go back to our ``desired_project_name`` and run the command below:
```
docker-compose up
```
This will build our docker container that our client and server application will be using to run without the ``ng serve`` and `` php -S 0.0.0.0:8080 -t public/ public/index.php``` commands.

While waiting, grab a cup of coffee and watch the magic building our application. :wink:

Once done with the build, lets update our host files. Located in ``C:\Windows\System32\drivers\etc`` for Windows.
```
127.0.0.1 uploader-api.local
127.0.0.1 uploader-client.local
```

## Congratulations! 

You can now access our client application using http://uploader-client.local:8081/, our api will be accessible through http://uploader-api.local:8081/.

## Built With

* [ZendFramerowk 2](https://github.com/zendframework/ZendSkeletonApplication) - The api web framework used
* [Angular](https://angular.io/) - The client web framework used

## License

This project is licensed under the MIT License
