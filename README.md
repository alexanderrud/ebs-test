## EBS Test

#### Hi, this is my test solution. Below you will find instructions how to run and test this project.
#### Hope you'll enjoy :)

### Step 1
##### Install all composer packages:
`composer install`

### Step 2
##### Do not forget to copy .env.example file into .env:
`cp .env.example .env`

### Step 3
##### Change values of environment variables stored in .env file to values stored in environment section of db service (docker-compose.yml file) 

### Step 4
##### Run docker containers with necessary environment:
`docker-compose up --build`

### Step 5
#### You should generate your own jwt secret token:
`docker exec -it php-fpm php artisan jwt:secret`

### Step 6
#### Run migrations:
`docker exec -it php-fpm php artisan migrate`

### Step 7
##### Inside docker container php-fpm run seeds:
`docker exec -it php-fpm php artisan db:seed`

## Now you can test it!
#### Inside project you can find postman collection file (EBS Test.postman_collection.json).
#### Please import it into your Postman app to test the endpoints.
#### After importing make sure that all environment variables are used and please generate jwt token by running login request.
#### If you receive an error after request running, it may happen in case of old jwt token. Please change Bearer Token with fresh one.  

### PS
#### I wanna thank the EBS Integrator team for giving me the opportunity to express myself. It was interesting task :)

<pre>
© Alexander Rudnicenco
</pre>
