# PDF Diff Application

### Local set up

For local development docker environment can be used.
To start container use the command  
`docker-compose up`

### Server set up
Copy all the files from `www` directory to the server. Make sure directory is writable.
Run the command to install dependencies  
`php composer.phar install --no-interaction --no-plugins --no-scripts --no-dev --prefer-dist`
### Running the application
Copy `config.php_tpl` to `config.php`.   
Set the path where you will put PDF files to compare details and DB details in `config.php` file.  
To save results in database flag `$saveResultsToDatabase` should be set to `true`.  
Database schema could be found in `dump/myDb.sql`  
Run the command  
`php index.php file1.pdf file2.pdf`
Result will be written in json and csv file in the same directory.
