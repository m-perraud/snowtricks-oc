# snowtricks-oc


### Install
In order to get started you first need to clone the project:
- `git clone https://github.com/m-perraud/snowtricks-oc.git`

We will need composer for the project. 
- `at the root, run  composer install`


### Prerequisites

-   Composer **version 2** and superior
-   PHP 8.1 and superior
-   A MySQL Server up and running
-   Symfony  6.1 and superior


### Database

The database informations are stored in the .env file :

- `DATABASE_URL="mysql://root:@127.0.0.1:3306/snowtricks"`

You have to modify those informations if you won't use the same. 
To set up the database, you will need to follow these steps : 

• Create the database if not already done : 
- `php bin/console doctrine:database:create`

• Make the migration : 
- `php bin/console doctrine:migrations:migrate`

• Get the data from the fixtures : 
- `php bin/console doctrine:fixtures:load`


 ### Mailer
 
 To set the mailing, we used Mailhog. 
 - `https://github.com/mailhog/MailHog`

 To use it, you simply have to install it, start it, and configure the following in your .env : 
 - `MAILER_DSN=smtp://localhost:1025`
 
You will be able to access the mailer on your localhost:1025. 
 
 ### Token
  
 In the service.yaml file, we need the JWT_SECRET from the .env :
 - `app.jwtsecret: '%env(JWT_SECRET)%'`
 
 You'll have to set it up in your .env. For the example, we used : 
  - `JWT_SECRET='OhLa83lleBroue11e!'`
  
 


 
