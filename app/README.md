<p class="has-line-data" data-line-start="0" data-line-end="2">Hello,<br>
This codebase is cover below feature.</p>
<ol>
<li class="has-line-data" data-line-start="2" data-line-end="4">
<p class="has-line-data" data-line-start="2" data-line-end="3">The program should process a local or remote XML file and store the data in a CSV file.</p>
</li>
<li class="has-line-data" data-line-start="4" data-line-end="5">
<p class="has-line-data" data-line-start="4" data-line-end="5">Command line adapter to save CSV file or insert into database.</p>
</li>
<li class="has-line-data" data-line-start="5" data-line-end="6">
<p class="has-line-data" data-line-start="5" data-line-end="6">Docker implementation</p>
</li>
<li class="has-line-data" data-line-start="6" data-line-end="7">
<p class="has-line-data" data-line-start="6" data-line-end="7">Monolog for Logger</p>
</li>
<li class="has-line-data" data-line-start="7" data-line-end="8">
<p class="has-line-data" data-line-start="7" data-line-end="8">Factory code pattern with Dependency injection</p>
</li>
<li class="has-line-data" data-line-start="8" data-line-end="8">
<p class="has-line-data" data-line-start="8" data-line-end="8">PHP Unit test cases</p>
</li>
</ol>
<p class="has-line-data" data-line-start="10" data-line-end="11">Clone Git Repo: <a href="https://github.com/kuldeepcs06/symfony_docker.git">https://github.com/kuldeepcs06/symfony_docker.git</a></p>
<p class="has-line-data" data-line-start="12" data-line-end="13">Build the containers:</p>
<p class="has-line-data" data-line-start="14" data-line-end="15">/usr/bin/docker-compose up -d --build</p>
<p class="has-line-data" data-line-start="16" data-line-end="20">Execute below commands on php container:<br>
docker-compose exec php composer install<br>
docker-compose exec php bin/console  make:migration<br>
docker-compose exec php bin/console  doctrine:migrations:migrate</p>
<p class="has-line-data" data-line-start="21" data-line-end="25">Set the “FTP details” in enviornment file for acsess file from remote location.<br>
FTP_HOST=<br>
FTP_USERNAME=<br>
FTP_PASSWORD=</p>
<p class="has-line-data" data-line-start="26" data-line-end="27">Command 1: To get data from remote FTP and save in mysql database</p>
<p class="has-line-data" data-line-start="28" data-line-end="29">/usr/bin/docker-compose exec php symfony console app-process-file --storage=database --mode=remote</p>
<p class="has-line-data" data-line-start="30" data-line-end="32">Response:<br>
[OK] 12 record exists, 0 records added</p>
<p class="has-line-data" data-line-start="33" data-line-end="34">Command 2: To get Data from local file existed in (app/public/xml-files/coffee_feed.xml) and save in mysql database</p>
<p class="has-line-data" data-line-start="35" data-line-end="38">/usr/bin/docker-compose exec php symfony console app-process-file --storage=database --mode=local<br>
Response:<br>
[OK] 11 record exists, 1 records added</p>
<p class="has-line-data" data-line-start="39" data-line-end="43">Command 3: Store data in CSV file data.csv<br>
/usr/bin/docker-compose exec php symfony console app-process-file --storage=csv --mode=local<br>
Response:<br>
[OK] CSV file created successfully</p>
<p class="has-line-data" data-line-start="44" data-line-end="48">Command 4: Store data in CSV file data.csv<br>
/usr/bin/docker-compose exec php symfony console app-process-file --storage=database --mode=local<br>
Response:<br>
[OK] 3449 record exists, 0 records added</p>

<p class="has-line-data" data-line-start="8" data-line-end="8">run PHPUnit test cases using /usr/bin/docker-compose exec php ./vendor/bin/phpunit</p>